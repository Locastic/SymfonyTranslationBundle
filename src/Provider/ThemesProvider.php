<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Provider;

use Locastic\SymfonyTranslationBundle\Model\Theme;
use Locastic\SymfonyTranslationBundle\Model\ThemeInterface;

final class ThemesProvider implements ThemesProviderInterface
{
    private DefaultTranslationDirectoryProviderInterface $defaultTranslationDirectoryProvider;

    public function __construct(DefaultTranslationDirectoryProviderInterface $defaultTranslationDirectoryProvider)
    {
        $this->defaultTranslationDirectoryProvider = $defaultTranslationDirectoryProvider;
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
        return new Theme(self::NAME_DEFAULT, $this->defaultTranslationDirectoryProvider->getDefaultDirectory());
    }
}
