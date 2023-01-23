<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Factory;

use Locastic\SymfonyTranslationBundle\Model\TranslationMigration;
use Locastic\SymfonyTranslationBundle\Model\TranslationMigrationInterface;

final class TranslationMigrationFactory implements TranslationMigrationFactoryInterface
{
    public function createNew(): TranslationMigrationInterface
    {
        return new TranslationMigration();
    }
}
