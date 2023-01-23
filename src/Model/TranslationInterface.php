<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Model;

interface TranslationInterface
{
    public function getDomainName(): ?string;

    public function setDomainName(?string $domainName): void;

    public function getKey(): ?string;

    public function setKey(?string $key): void;

    /**
     * @return array|TranslationValueInterface[]
     */
    public function getValues(): array;

    public function getKeyByLocaleAndTheme(string $localeCode, string $themeName): ?int;

    public function hasLocaleAndTheme(string $localeCode, string $themeName): bool;

    public function hasValue(TranslationValueInterface $translationValue): bool;

    public function addValue(TranslationValueInterface $translationValue): void;

    public function removeValue(TranslationValueInterface $translationValue): void;

    public function getValueByLocaleAndTheme(string $localeCode, string $themeName): ?string;
}
