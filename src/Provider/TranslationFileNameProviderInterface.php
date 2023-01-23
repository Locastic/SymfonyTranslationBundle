<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Provider;

use Locastic\SymfonyTranslationBundle\Exception\GenerateTranslationFileNameException;
use Locastic\SymfonyTranslationBundle\Exception\TranslationNotFoundException;
use Locastic\SymfonyTranslationBundle\Model\TranslationValueInterface;

interface TranslationFileNameProviderInterface
{
    /**
     * @throws GenerateTranslationFileNameException
     * @throws TranslationNotFoundException
     */
    public function getFileName(TranslationValueInterface $translationValue): string;

    public function getFromValues(string $directory, string $domain, string $locale, string $format): string;
}
