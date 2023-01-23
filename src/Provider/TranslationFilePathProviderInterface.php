<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Provider;

use Locastic\SymfonyTranslationBundle\Model\TranslationValueInterface;

interface TranslationFilePathProviderInterface
{
    public function getFilePath(TranslationValueInterface $translationValue): string;
}
