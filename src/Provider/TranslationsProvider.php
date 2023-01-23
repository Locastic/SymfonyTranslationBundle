<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Provider;

use InvalidArgumentException;
use Locastic\SymfonyTranslationBundle\Utils\ArrayUtils;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;
use function array_key_exists;
use function array_key_first;
use function array_replace_recursive;
use function in_array;

final class TranslationsProvider implements TranslationsProviderInterface
{
    private array $bundles;

    private TranslationDomainsProviderInterface $translationDomainsProvider;

    private LocalesProviderInterface $localesProvider;

    private FileLocator $fileLocator;

    private TranslationFileNameProviderInterface $translationFileNameProvider;

    private string $appTranslationsDirectory;

    private ThemesProviderInterface $themeProvider;

    public function __construct(
        array $enabledBundles,
        TranslationDomainsProviderInterface $translationDomainsProvider,
        LocalesProviderInterface $localesProvider,
        FileLocator $fileLocator,
        TranslationFileNameProviderInterface $translationFileNameProvider,
        string $appTranslationsDirectory,
        ThemesProviderInterface $themeProvider
    ) {
        $this->bundles = $enabledBundles;
        $this->translationDomainsProvider = $translationDomainsProvider;
        $this->localesProvider = $localesProvider;
        $this->fileLocator = $fileLocator;
        $this->translationFileNameProvider = $translationFileNameProvider;
        $this->appTranslationsDirectory = $appTranslationsDirectory;
        $this->themeProvider = $themeProvider;
    }

    public function getTranslations(string $defaultLocaleCode, array $locales): array
    {
        $bundleTranslations = [];
        foreach ($this->bundles as $bundleName => $bundle) {
            $bundleTranslations = array_replace_recursive(
                $bundleTranslations,
                $this->getBundleTranslations($bundleName, $defaultLocaleCode, $locales)
            );
        }
        $appTranslations = $this->getDirectoryTranslations($this->appTranslationsDirectory, $defaultLocaleCode, $locales);

        $themes = $this->themeProvider->getAll();
        $translations = [];
        foreach ($themes as $theme) {
            $translationDirectory = $theme->getPath() . '/translations/';
            $themeTranslations = $this->getDirectoryTranslations($translationDirectory, $defaultLocaleCode, $locales);
            $themeTranslations = $this->removeEmptyKeys($themeTranslations);

            $mergedTranslations = array_replace_recursive($bundleTranslations, $appTranslations, $themeTranslations);
            $mergedTranslations = $this->fillMissingKeys($mergedTranslations, $locales);
            $translations[$theme->getName()] = $mergedTranslations;
        }
        $translations = $this->fillMissingKeys($translations, $locales);

        ArrayUtils::recursiveKsort($translations);

        return $translations;
    }

    public function getBundleTranslations(string $bundleName, string $localeCode, array $locales): array
    {
        if (!$this->doesBundleHaveTranslations($bundleName)) {
            return [];
        }
        $translationsDirectory = $this->getBundleTranslationsDirectory($bundleName);

        return $this->getDirectoryTranslations($translationsDirectory, $localeCode, $locales);
    }

    public function getDirectoryTranslations(string $directory, string $localeCode, array $locales): array
    {
        $domains = $this->translationDomainsProvider->toArray($directory);
        $defaultLocales = $this->localesProvider->getLocalesFromCode($localeCode);

        $directoryTranslations = [];
        foreach ($domains as $domain) {
            foreach ($defaultLocales as $defaultLocale) {
                $translations = $this->getYamlTranslations($directory, $domain, $defaultLocale);
                $translations = array_replace_recursive($translations, $this->getXmlTranslations($directory, $domain, $defaultLocale));

                if (!array_key_exists($domain, $directoryTranslations)) {
                    $directoryTranslations[$domain] = [];
                }
                $translations = ArrayUtils::arrayFlatten($translations);
                foreach ($translations as $key => $value) {
                    $translations[$key] = [$localeCode => $value];
                }

                $directoryTranslations[$domain] = array_replace_recursive($directoryTranslations[$domain], $translations);
            }

            foreach ($locales as $locale) {
                $availableLocales = $this->localesProvider->getLocalesFromCode($locale);
                foreach ($availableLocales as $availableLocale) {
                    $translations = $this->getYamlTranslations($directory, $domain, $availableLocale);
                    $translations = array_replace_recursive($translations, $this->getXmlTranslations($directory, $domain, $availableLocale));

                    if (!array_key_exists($domain, $directoryTranslations)) {
                        continue;
                    }
                    $translations = ArrayUtils::arrayFlatten($translations);
                    foreach ($translations as $key => $value) {
                        $directoryTranslations[$domain][$key][$locale] = $value;
                    }
                }
            }
        }

        return $directoryTranslations;
    }

    public function doesBundleHaveTranslations(string $bundleName): bool
    {
        try {
            $this->fileLocator->locate(sprintf('@%s/Resources/translations/', $bundleName));

            return true;
        } catch (InvalidArgumentException $exception) {
            return false;
        }
    }

    public function getBundleTranslationsDirectory(string $bundleName): ?string
    {
        if (!$this->doesBundleHaveTranslations($bundleName)) {
            return null;
        }

        return $this->fileLocator->locate(sprintf('@%s/Resources/translations/', $bundleName));
    }

    public function getYamlTranslations(string $directory, string $domain, string $locale): array
    {
        $translations = [];

        $formats = ['yml', 'yaml'];
        foreach ($formats as $format) {
            $fileName = $this->translationFileNameProvider->getFromValues($directory, $domain, $locale, $format);
            $translations = array_replace_recursive($translations, $this->getTranslationFileContent($fileName));
        }

        return $translations;
    }

    public function getTranslationFileContent(string $filePath, string $type = self::TYPE_YAML): array
    {
        if (!file_exists($filePath)) {
            return [];
        }

        switch ($type) {
            case self::TYPE_XML:
                throw new \LogicException('This has not been implemented yet');
                return [];
            case self::TYPE_YAML:
            default:
                return Yaml::parse(file_get_contents($filePath));
        }
    }

    public function getXmlTranslations(string $directory, string $domain, string $locale): array
    {
        $translations = [];

        $formats = ['xml'];
        foreach ($formats as $format) {
            $fileName = $this->translationFileNameProvider->getFromValues($directory, $domain, $locale, $format);
            $translations = array_replace_recursive($translations, $this->getTranslationFileContent($fileName, self::TYPE_XML));
        }

        return $translations;
    }

    private function fillMissingKeys(array $translations, array $locales): array
    {
        foreach ($translations as $key => $value) {
            if (in_array(array_key_first($value), $locales)) {
                foreach ($locales as $locale) {
                    if (!array_key_exists($locale, $value)) {
                        $translations[$key][$locale] = '';
                    }
                }
            } else {
                $translations[$key] = $this->fillMissingKeys($value, $locales);
            }
        }

        return $translations;
    }

    public function removeEmptyKeys(array $translations): array
    {
        foreach ($translations as $key => $value) {
            if (empty($value)) {
                unset($translations[$key]);
            } elseif (is_array($value)) {
                $translations[$key] = $this->removeEmptyKeys($value);
            }
        }

        return $translations;
    }

    public function defineAllKeys(array $translations, array $locales): array
    {
        $keys = [];
        foreach ($translations as $themeTranslations) {
            foreach ($themeTranslations as $domain => $domainTranslations) {
                if (!array_key_exists($domain, $keys)) {
                    $keys[$domain] = [];
                }
                foreach ($domainTranslations as $key => $keyTranslations) {
                    if (!in_array($key, $keys)) {
                        $keys[$domain][] = $key;
                    }
                }
            }
        }

        $translationTemplate = [];
        foreach ($locales as $locale) {
            $translationTemplate[$locale] = '';
        }
        foreach ($translations as $themeName => $themeTranslations) {
            foreach ($themeTranslations as $domain => $domainTranslations) {
                foreach ($keys[$domain] as $key) {
                    if (!array_key_exists($key, $domainTranslations)) {
                        $translations[$themeName][$domain][$key] = $translationTemplate;
                    }
                }
            }
        }

        return $translations;
    }
}
