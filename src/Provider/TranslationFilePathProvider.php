<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Provider;

use Locastic\SymfonyTranslationBundle\Model\TranslationValueInterface;

final class TranslationFilePathProvider implements TranslationFilePathProviderInterface
{
    private DefaultTranslationDirectoryProviderInterface $defaultTranslationDirectoryProvider;

    public function __construct(DefaultTranslationDirectoryProviderInterface $defaultTranslationDirectoryProvider)
    {
        $this->defaultTranslationDirectoryProvider = $defaultTranslationDirectoryProvider;
    }

    public function getFilePath(TranslationValueInterface $translationValue): string
    {
        return $this->defaultTranslationDirectoryProvider->getDefaultDirectory();
    }
}
