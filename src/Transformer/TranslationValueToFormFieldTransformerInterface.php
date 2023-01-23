<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Transformer;

use Locastic\SymfonyTranslationBundle\Model\TranslationValueInterface;

interface TranslationValueToFormFieldTransformerInterface
{
    public function transform(TranslationValueInterface $translationValue): string;

    public function reverseTransform(array $submittedField): ?TranslationValueInterface;
}
