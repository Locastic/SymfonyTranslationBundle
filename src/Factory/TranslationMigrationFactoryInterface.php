<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Factory;

use Locastic\SymfonyTranslationBundle\Model\TranslationMigrationInterface;

interface TranslationMigrationFactoryInterface
{
    public function createNew(): TranslationMigrationInterface;
}
