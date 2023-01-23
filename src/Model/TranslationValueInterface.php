<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Model;

interface TranslationValueInterface
{
    public function getLocaleCode(): ?string;

    public function setLocaleCode(?string $localeCode): void;

    public function getValue(): ?string;

    public function setValue(?string $value): void;

    public function getTheme(): ?string;

    public function setTheme(?string $theme): void;

    public function getTranslation(): ?TranslationInterface;

    public function setTranslation(?TranslationInterface $translation): void;
}
