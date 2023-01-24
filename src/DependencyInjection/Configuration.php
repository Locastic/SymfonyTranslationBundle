<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('locastic_symfony_translation');
        if (\method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('locastic_symfony_translation');
        }

        $rootNode
            ->children()
                ->scalarNode('default_locale')
                    ->defaultValue('en_US')
                ->end()
                ->arrayNode('locales')->scalarPrototype()->end()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
