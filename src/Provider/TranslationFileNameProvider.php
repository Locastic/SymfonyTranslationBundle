<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Provider;

use Locastic\SymfonyTranslationBundle\Exception\GenerateTranslationFileNameException;
use Locastic\SymfonyTranslationBundle\Exception\TranslationNotFoundException;
use Locastic\SymfonyTranslationBundle\Model\TranslationValueInterface;

use function sprintf;

final class TranslationFileNameProvider implements TranslationFileNameProviderInterface
{
    public function getFileName(TranslationValueInterface $translationValue): string
    {
        if (null === $translationValue->getTranslation()) {
            throw new TranslationNotFoundException();
        }

        if (null === $translationValue->getTranslation()->getDomainName() || null === $translationValue->getLocaleCode()) {
            throw new GenerateTranslationFileNameException();
        }

        return sprintf('%s.%s.yaml', $translationValue->getTranslation()->getDomainName(), $translationValue->getLocaleCode());
    }

    public function getFromValues(string $directory, string $domain, string $locale, string $format): string
    {
        return sprintf('%s%s.%s.%s', $directory, $domain, $locale, $format);
    }
}
