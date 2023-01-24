<?php

namespace Locastic\SymfonyTranslationBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class LocasticSymfonyTranslationBundleExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $container->setParameter('locastic_symfony_translation.default_locale', $config['default_locale']);
        $container->setParameter('locastic_symfony_translation.locales', $config['locales']);

        $loader->load('services.xml');
    }
}
