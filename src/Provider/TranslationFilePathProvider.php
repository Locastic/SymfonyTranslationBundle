<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Provider;

use Locastic\SymfonyTranslationBundle\Model\TranslationValueInterface;

final class TranslationFilePathProvider implements TranslationFilePathProviderInterface
{
    private ThemesProviderInterface $themesProvider;

    public function __construct(ThemesProviderInterface $themesProvider)
    {
        $this->themesProvider = $themesProvider;
    }

    public function getFilePath(TranslationValueInterface $translationValue): string
    {
        $theme = $this->themesProvider->findOneByName($translationValue->getTheme());
        if (null === $theme) {
            $theme = $this->themesProvider->getDefaultTheme();
        }

        return $theme->getPath() . '/translations/';
    }
}
