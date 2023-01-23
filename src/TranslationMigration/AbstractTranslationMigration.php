<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\TranslationMigration;

use Locastic\SymfonyTranslationBundle\Model\Translation;
use Locastic\SymfonyTranslationBundle\Model\TranslationValue;
use ReflectionClass;

use function str_replace;

abstract class AbstractTranslationMigration
{
    protected ExecutorInterface $migrationExecutor;

    public function __construct(ExecutorInterface $migrationExecutor)
    {
        $this->migrationExecutor = $migrationExecutor;
    }

    abstract public function up(): void;

    public function addTranslation(
        string $key,
        string $domain,
        string $localeCode,
        string $value,
        string $theme
    ): void {
        $translation = new Translation();
        $translation->setDomainName($domain);
        $translation->setKey($key);

        $translationValue = new TranslationValue();
        $translationValue->setLocaleCode($localeCode);
        $translationValue->setValue($value);
        $translationValue->setTheme($theme);

        $translation->addValue($translationValue);

        $this->migrationExecutor->addTranslation($translation);
    }

    public function getVersionNumber(): string
    {
        $reflectionClass = new ReflectionClass(get_class($this));

        return str_replace('Version', '', $reflectionClass->getShortName());
    }
}
