<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Model;

class Translation implements TranslationInterface
{
    private ?string $domainName = null;

    private ?string $key = null;

    /** @var array|TranslationValueInterface[] */
    private array $values = [];

    public function getDomainName(): ?string
    {
        return $this->domainName;
    }

    public function setDomainName(?string $domainName): void
    {
        $this->domainName = $domainName;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(?string $key): void
    {
        $this->key = $key;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function getValuesByTheme(string $themeName): array
    {
        return \array_filter($this->getValues(), function (TranslationValueInterface $translationValue) use ($themeName): bool {
            return $themeName === $translationValue->getTheme();
        });
    }

    public function getKeyByLocaleAndTheme(string $localeCode, string $themeName): ?int
    {
        foreach ($this->getValues() as $key => $value) {
            if ($value->getLocaleCode() === $localeCode && $value->getTheme() === $themeName) {
                return $key;
            }
        }

        return null;
    }

    public function hasLocaleAndTheme(string $localeCode, string $themeName): bool
    {
        return null !== $this->getKeyByLocaleAndTheme($localeCode, $themeName);
    }

    public function hasValue(TranslationValueInterface $translationValue): bool
    {
        return $this->hasLocaleAndTheme($translationValue->getLocaleCode(), $translationValue->getTheme()) !== null;
    }

    public function addValue(TranslationValueInterface $translationValue): void
    {
        if (!$this->hasLocaleAndTheme($translationValue->getLocaleCode(), $translationValue->getTheme())) {
            $this->values[] = $translationValue;
            $translationValue->setTranslation($this);
        }
    }

    public function removeValue(TranslationValueInterface $translationValue): void
    {
        if (null !== $key = $this->getKeyByLocaleAndTheme($translationValue->getLocaleCode(), $translationValue->getTheme())) {
            unset($this->values[$key]);
            $translationValue->setTranslation(null);
        }
    }

    public function getValueByLocaleAndTheme(string $localeCode, string $themeName): ?string
    {
        if (null !== $key = $this->getKeyByLocaleAndTheme($localeCode, $themeName)) {
            return $this->values[$key];
        }

        return null;
    }
}
