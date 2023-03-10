<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\TranslationMigration;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Locastic\SymfonyTranslationBundle\Factory\TranslationMigrationFactoryInterface;
use Locastic\SymfonyTranslationBundle\Model\Translation;
use Locastic\SymfonyTranslationBundle\Model\TranslationMigrationInterface;
use Locastic\SymfonyTranslationBundle\Saver\TranslationValueSaverInterface;

final class Executor implements ExecutorInterface
{
    /** @var Translation[] */
    private array $translations = [];

    private TranslationValueSaverInterface $translationValueSaver;

    private TranslationMigrationFactoryInterface $translationMigrationFactory;

    private ManagerRegistry $managerRegistry;

    public function __construct(
        TranslationValueSaverInterface $translationValueSaver,
        TranslationMigrationFactoryInterface $translationMigrationFactory,
        ManagerRegistry $managerRegistry
    ) {
        $this->translationValueSaver = $translationValueSaver;
        $this->translationMigrationFactory = $translationMigrationFactory;
        $this->managerRegistry = $managerRegistry;
    }

    public function addTranslation(Translation $translation): void
    {
        $this->translations[] = $translation;
    }

    public function clearTranslations(): void
    {
        $this->translations = [];
    }

    public function execute(AbstractTranslationMigration $migration, bool $resync = false): void
    {
        try {
            $this->executeMigration($migration, $resync);
        } catch (Exception $exception) {
            $this->skipMigration($migration);
        }
    }

    private function executeMigration(AbstractTranslationMigration $migration, bool $resync = false): void
    {
        if ($this->hasAlreadyBeenPlayed($migration) && !$resync) {
            return;
        }
        $migration->up();

        foreach ($this->translations as $translation) {
            foreach ($translation->getValues() as $translationValue) {
                $this->translationValueSaver->saveTranslationValue($translationValue, !$resync);
            }
        }

        if (!$this->hasAlreadyBeenPlayed($migration)) {
            $this->markVersion($migration);
        }
    }

    private function skipMigration(AbstractTranslationMigration $migration): void
    {
        $this->markVersion($migration);
    }

    private function markVersion(AbstractTranslationMigration $migration): void
    {
        $translationMigration = $this->translationMigrationFactory->createNew();
        $translationMigration->setNumber($migration->getVersionNumber());

        $this->managerRegistry->getManager()->persist($translationMigration);
        $this->managerRegistry->getManager()->flush();
    }

    private function hasAlreadyBeenPlayed(AbstractTranslationMigration $migration): bool
    {
        return $this->managerRegistry
                ->getRepository(TranslationMigrationInterface::class)
                ->findOneBy(['number' => $migration->getVersionNumber()]) instanceof TranslationMigrationInterface;
    }
}
