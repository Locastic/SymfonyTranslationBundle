<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Provider;

interface TranslationsProviderInterface
{
    public const TYPE_YAML = 'yaml';

    public const TYPE_XML = 'xml';

    public function getTranslations(string $defaultLocaleCode, array $locales): array;

    public function getBundleTranslations(string $bundleName, string $localeCode, array $locales): array;

    public function getDirectoryTranslations(string $directory, string $localeCode, array $locales): array;

    public function doesBundleHaveTranslations(string $bundleName): bool;

    public function getBundleTranslationsDirectory(string $bundleName): ?string;

    public function getYamlTranslations(string $directory, string $domain, string $locale): array;

    public function getTranslationFileContent(string $filePath, string $type = self::TYPE_YAML): array;

    public function getXmlTranslations(string $directory, string $domain, string $locale): array;

    public function removeEmptyKeys(array $translations): array;

    public function defineAllKeys(array $translations, array $locales): array;
}
