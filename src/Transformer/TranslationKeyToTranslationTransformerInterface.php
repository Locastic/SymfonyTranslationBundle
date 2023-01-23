<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Transformer;

use Locastic\SymfonyTranslationBundle\Model\TranslationInterface;

interface TranslationKeyToTranslationTransformerInterface
{
    /**
     * @return array|TranslationInterface[]
     */
    public function transformMultiple(array $translationKeys): array;
}
