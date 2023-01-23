<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Provider;

use Sylius\Bundle\ThemeBundle\Model\ThemeInterface;

interface ThemesProviderInterface
{
    public const NAME_DEFAULT = 'app/default';

    /**
     * @return array|ThemeInterface[]
     */
    public function getAll(): array;

    public function findOneByName(string $name): ?ThemeInterface;

    public function getDefaultTheme(): ThemeInterface;
}
