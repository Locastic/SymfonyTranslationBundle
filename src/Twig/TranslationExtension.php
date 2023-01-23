<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Twig;

use Locastic\SymfonyTranslationBundle\Model\TranslationValueInterface;
use Locastic\SymfonyTranslationBundle\Transformer\TranslationValueToFormFieldTransformerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class TranslationExtension extends AbstractExtension
{
    private TranslationValueToFormFieldTransformerInterface $translationValueToFormFieldTransformer;

    public function __construct(TranslationValueToFormFieldTransformerInterface $translationValueToFormFieldTransformer)
    {
        $this->translationValueToFormFieldTransformer = $translationValueToFormFieldTransformer;
    }

    public function getFilters(): iterable
    {
        return [
            new TwigFilter('locastic_sylius_translation_value_field_name', [$this, 'getTranslationValueFieldName'])
        ];
    }

    public function getTranslationValueFieldName(TranslationValueInterface $translationValue): string
    {
        return $this->translationValueToFormFieldTransformer->transform($translationValue);
    }
}
