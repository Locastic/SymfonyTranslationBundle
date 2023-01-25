<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Model;

class Theme implements ThemeInterface
{
    protected string $name;

    protected string $path;

    public function __construct(string $name, string $path)
    {
        $this->name = $name;
        $this->path = $path;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }
}
