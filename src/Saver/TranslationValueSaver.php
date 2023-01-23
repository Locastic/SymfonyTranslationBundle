<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Saver;

use Locastic\SymfonyTranslationBundle\Exception\GenerateTranslationFileNameException;
use Locastic\SymfonyTranslationBundle\Exception\TranslationNotFoundException;
use Locastic\SymfonyTranslationBundle\Model\TranslationValueInterface;
use Locastic\SymfonyTranslationBundle\Provider\TranslationFileNameProviderInterface;
use Locastic\SymfonyTranslationBundle\Provider\TranslationFilePathProviderInterface;
use Locastic\SymfonyTranslationBundle\Provider\TranslationsProviderInterface;
use Locastic\SymfonyTranslationBundle\Utils\ArrayUtils;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

use function array_key_exists;
use function array_replace_recursive;
use function is_string;

final class TranslationValueSaver implements TranslationValueSaverInterface
{
    private string $directory;

    private TranslationFileNameProviderInterface $translationFileNameProvider;

    private TranslationFilePathProviderInterface $translationFilePathProvider;

    private TranslationsProviderInterface $translationsProvider;

    public function __construct(
        string $directory,
        TranslationFileNameProviderInterface $translationFileNameProvider,
        TranslationFilePathProviderInterface $translationFilePathProvider,
        TranslationsProviderInterface $translationsProvider
    ) {
        $this->directory = $directory;
        $this->translationFileNameProvider = $translationFileNameProvider;
        $this->translationFilePathProvider = $translationFilePathProvider;
        $this->translationsProvider = $translationsProvider;
    }

    /**
     * @throws TranslationNotFoundException
     * @throws GenerateTranslationFileNameException
     */
    public function saveTranslationValue(TranslationValueInterface $translationValue, bool $overrideExisting = true): void
    {
        $translation = $translationValue->getTranslation();
        $existingTranslations = $this->translationsProvider->getTranslations($translationValue->getLocaleCode(), [$translationValue->getLocaleCode()]);
        $existingTranslations = $existingTranslations[$translationValue->getTheme()][$translation->getDomainName()] ?? [];

        if ($overrideExisting) {
            $newTranslations = array_replace_recursive($existingTranslations, [
                $translation->getKey() => [
                    $translationValue->getLocaleCode() => $translationValue->getValue(),
                ],
            ]);
        } else {
            // just add new value if it does not exist in the original translations
            $newTranslations = $existingTranslations;

            if (!array_key_exists($translation->getKey(), $existingTranslations)
                || !array_key_exists($translationValue->getLocaleCode(), $existingTranslations[$translation->getKey()])
            ) {
                $newTranslations[$translation->getKey()][$translationValue->getLocaleCode()] = $translationValue->getValue();
            }
        }

        // Remove empty keys to fall back to default ones
        $newTranslations = $this->translationsProvider->removeEmptyKeys($newTranslations);

        $result = [];
        foreach ($newTranslations as $newTranslationKey => $newTranslation) {
            if (!array_key_exists($translationValue->getLocaleCode(), $newTranslation)) {
                continue;
            }
            if (!is_string($newTranslation[$translationValue->getLocaleCode()])) {
                continue;
            }
            $result = array_replace_recursive($result, ArrayUtils::keyToArray($newTranslationKey, $newTranslation[$translationValue->getLocaleCode()]));
        }
        ArrayUtils::recursiveKsort($result);

        $fileName = $this->translationFileNameProvider->getFileName($translationValue);
        $filePath = $this->translationFilePathProvider->getFilePath($translationValue);

        $filesystem = new Filesystem();
        $filesystem->dumpFile($filePath . $fileName, Yaml::dump($result, 8));
    }

    /**
     * @throws TranslationNotFoundException
     * @throws GenerateTranslationFileNameException
     */
    public function saveTranslations(array $translations): void
    {
        $files = [];
        foreach ($translations as $translation) {
            foreach ($translation->getValues() as $translationValue) {
                $fileName = $this->translationFileNameProvider->getFileName($translationValue);
                if (!array_key_exists($fileName, $files)) {
                    $files[$fileName] = [];
                }

                $files[$fileName] = array_replace_recursive($files[$fileName], ArrayUtils::keyToArray($translation->getKey(), $translationValue->getValue()));
            }
        }

        $filesystem = new Filesystem();
        foreach ($files as $fileName => $fileTranslations) {
            $filesystem->dumpFile($this->directory . '/' . $fileName, Yaml::dump($fileTranslations, 10));
        }
    }
}
