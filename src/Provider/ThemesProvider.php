<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Provider;

use Locastic\SymfonyTranslationBundle\Factory\ThemeFactoryInterface;
use Locastic\SymfonyTranslationBundle\Model\ThemeInterface;

final class ThemesProvider implements ThemesProviderInterface
{
    private DefaultTranslationDirectoryProviderInterface $defaultTranslationDirectoryProvider;

    private ThemeFactoryInterface $themeFactory;

    public function __construct(
        DefaultTranslationDirectoryProviderInterface $defaultTranslationDirectoryProvider,
        ThemeFactoryInterface $themeFactory
    ) {
        $this->defaultTranslationDirectoryProvider = $defaultTranslationDirectoryProvider;
        $this->themeFactory = $themeFactory;
    }

    public function getAll(): array
    {
        return [self::NAME_DEFAULT => $this->getDefaultTheme()];
    }

    public function findOneByName(string $name): ?ThemeInterface
    {
        return $this->getDefaultTheme();
    }

    public function getDefaultTheme(): ThemeInterface
    {
        return $this->themeFactory->createNew(self::NAME_DEFAULT, $this->defaultTranslationDirectoryProvider->getDefaultDirectory());
    }
}
