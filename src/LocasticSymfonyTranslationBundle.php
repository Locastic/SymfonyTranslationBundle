<?php

namespace Locastic\SymfonyTranslationBundle;

use Locastic\SymfonyTranslationBundle\DependencyInjection\LocasticSymfonyTranslationBundleExtension;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LocasticSymfonyTranslationBundle extends Bundle
{
    public function getContainerExtension(): Extension
    {
        return new LocasticSymfonyTranslationBundleExtension();
    }
}
