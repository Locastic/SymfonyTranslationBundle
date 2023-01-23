<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\TranslationMigration;

use Locastic\SymfonyTranslationBundle\Model\Translation;

interface ExecutorInterface
{
    public function addTranslation(Translation $translation): void;

    public function clearTranslations(): void;

    public function execute(AbstractTranslationMigration $migration, bool $resync = false): void;
}
