<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Model;

interface ThemeInterface
{
    public function getName(): string;

    public function setName(string $name): void;

    public function getPath(): string;

    public function setPath(string $path): void;
}
