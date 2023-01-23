<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Form\Type;

use Locastic\SymfonyTranslationBundle\Model\SearchTranslationInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SearchTranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('search', TextType::class, [
            'label' => 'locastic_sylius_translation.form.search_translation',
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', SearchTranslationInterface::class);
    }
}
