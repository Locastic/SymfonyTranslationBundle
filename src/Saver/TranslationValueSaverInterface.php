<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Saver;

use Locastic\SymfonyTranslationBundle\Model\TranslationInterface;
use Locastic\SymfonyTranslationBundle\Model\TranslationValueInterface;

interface TranslationValueSaverInterface
{
    public function saveTranslationValue(TranslationValueInterface $translationValue, bool $overrideExisting = true): void;

    /**
     * @param array|TranslationInterface[] $translations
     */
    public function saveTranslations(array $translations): void;
}
