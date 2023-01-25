<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Provider;

interface DefaultTranslationDirectoryProviderInterface
{
    public function getDefaultDirectory(): string;
}
