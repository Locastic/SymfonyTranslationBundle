<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Provider;

use Locastic\SymfonyTranslationBundle\Model\TranslationValueInterface;

final class TranslationFilePathProvider implements TranslationFilePathProviderInterface
{
    private string $translationsDirectory;

    public function __construct(string $translationsDirectory)
    {
        $this->translationsDirectory = $translationsDirectory;
    }

    public function getFilePath(TranslationValueInterface $translationValue): string
    {
        return $this->getDefaultDirectory();
    }

    public function getDefaultDirectory(): string
    {
        return $this->translationsDirectory;
    }
}
