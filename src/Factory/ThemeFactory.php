<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Factory;

use Locastic\SymfonyTranslationBundle\Model\Theme;
use Locastic\SymfonyTranslationBundle\Model\ThemeInterface;

final class ThemeFactory implements ThemeFactoryInterface
{
    public function createNew(string $name, string $path): ThemeInterface
    {
        return new Theme($name, $path);
    }
}
