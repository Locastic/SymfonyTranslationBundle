<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Model;

class TranslationValue implements TranslationValueInterface
{
    private ?string $localeCode = null;

    private ?string $value = null;

    private ?string $theme = null;

    private ?TranslationInterface $translation = null;

    public function getLocaleCode(): ?string
    {
        return $this->localeCode;
    }

    public function setLocaleCode(?string $localeCode): void
    {
        $this->localeCode = $localeCode;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(?string $theme): void
    {
        $this->theme = $theme;
    }

    public function getTranslation(): ?TranslationInterface
    {
        return $this->translation;
    }

    public function setTranslation(?TranslationInterface $translation): void
    {
        $this->translation = $translation;
    }
}
