<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Provider;

final class DefaultTranslationDirectoryProvider implements DefaultTranslationDirectoryProviderInterface
{
    private string $translationsDirectory;

    public function __construct(string $translationsDirectory)
    {
        $this->translationsDirectory = $translationsDirectory;
    }

    public function getDefaultDirectory(): string
    {
        return $this->translationsDirectory;
    }
}
