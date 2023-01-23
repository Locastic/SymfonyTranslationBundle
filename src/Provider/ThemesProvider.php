<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Provider;

use Sylius\Bundle\ThemeBundle\Factory\ThemeFactoryInterface;
use Sylius\Bundle\ThemeBundle\Model\ThemeInterface;
use Sylius\Bundle\ThemeBundle\Repository\ThemeRepositoryInterface;

final class ThemesProvider implements ThemesProviderInterface
{
    private ThemeRepositoryInterface $themeRepository;

    private ThemeFactoryInterface $themeFactory;

    private string $baseDirectory;

    public function __construct(
        ThemeRepositoryInterface $themeRepository,
        ThemeFactoryInterface $themeFactory,
        string $baseDirectory
    ) {
        $this->themeRepository = $themeRepository;
        $this->themeFactory = $themeFactory;
        $this->baseDirectory = $baseDirectory;
    }

    public function getAll(): array
    {
        $themes = [self::NAME_DEFAULT => $this->getDefaultTheme()];

        return array_merge($themes, $this->themeRepository->findAll());
    }

    public function findOneByName(string $name): ?ThemeInterface
    {
        if (self::NAME_DEFAULT === $name) {
            return $this->getDefaultTheme();
        }

        return $this->themeRepository->findOneByName($name);
    }

    public function getDefaultTheme(): ThemeInterface
    {
        return $this->themeFactory->create(self::NAME_DEFAULT, $this->baseDirectory);
    }
}
