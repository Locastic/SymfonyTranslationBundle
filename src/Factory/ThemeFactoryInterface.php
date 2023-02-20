<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Factory;

use Locastic\SymfonyTranslationBundle\Model\ThemeInterface;

interface ThemeFactoryInterface
{
    public function createNew(string $name, string $path): ThemeInterface;
}
