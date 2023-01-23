<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Provider;

interface LocalesProviderInterface
{
    public function getLocalesFromCode(string $localeCode): array;
}
