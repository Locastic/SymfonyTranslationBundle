<h1 align="center">
Locastic Translation Bundle<br>
    <a href="https://packagist.org/packages/locastic/symfony-translation-bundle" title="License" target="_blank">
        <img src="https://img.shields.io/packagist/l/locastic/symfony-translation-bundle.svg" />
    </a>
    <a href="https://packagist.org/packages/locastic/symfony-translation-bundle" title="Version" target="_blank">
        <img src="https://img.shields.io/packagist/v/Locastic/symfony-translation-bundle.svg" />
    </a>
    <a href="https://scrutinizer-ci.com/g/Locastic/SymfonyTranslationBundle/" title="Scrutinizer" target="_blank">
        <img src="https://img.shields.io/scrutinizer/g/Locastic/SymfonyTranslationBundle" />
    </a>
    <a href="https://packagist.org/packages/locastic/symfony-translation-bundle" title="Total Downloads" target="_blank">
        <img src="https://poser.pugx.org/locastic/symfony-translation-bundle/downloads" />
    </a>
</h1>

This bundle gives the basis to allow translating your messages from any admin panel.
You will now be able to create translation migrations, the same way you create your database migrations.
It also provides a few services to help you expose and manage your translations.

## Installation

```bash
composer require locastic/symfony-translation-bundle
```

## Configuration

```yaml
imports:
    - { resource: "@LocasticSyliusTranslationPlugin/Resources/config/config.yaml" }

locastic_sylius_translation:
    default_locale: en # The default locale to use
    locales: # The list locales supported by your application
        - en 
```

_Note:_ This bundle supports locales the same way as Symfony, meaning that `en` will be the fallback for `en_US` and `en_GB`.

## Usage

### Creating a translation migration

```php
<?php

declare(strict_types=1);

namespace App\TranslationMigrations;

use Locastic\SymfonyTranslationBundle\Provider\ThemesProviderInterface;
use Locastic\SymfonyTranslationBundle\TranslationMigration\AbstractTranslationMigration;

final class Version20230201074700 extends AbstractTranslationMigration
{
    public function up(): void
    {
        $this->addTranslation('test.translation', 'messages', 'en', 'This is a test translation', ThemesProviderInterface::NAME_DEFAULT);
    }
}
```

Then you can run the migration with the following command:

```bash
bin/console locastic:symfony-translation:migration:migrate
```

_Note:_ down for migrations is not supported yet.

### Exposing translations

You will need to create a controller to expose your translations. Here is an example:

```php
<?php

declare(strict_types=1);

namespace App\Controller;

use Locastic\SymfonyTranslationBundle\Form\Type\SearchTranslationType;
use Locastic\SymfonyTranslationBundle\Model\SearchTranslation;
use Locastic\SymfonyTranslationBundle\Utils\SearchTranslationsUtilsInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class IndexTranslationAction
{
    public function __construct(
        private SearchTranslationsUtilsInterface $searchTranslationsUtils,
        private Environment $twig,
        private FormFactoryInterface $formFactory,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $search = new SearchTranslation();
        $searchForm = $this->formFactory->create(SearchTranslationType::class, $search);
        $searchForm->handleRequest($request);

        $pagerFanta = $this->searchTranslationsUtils->searchTranslationsFromRequest($request, $search, $searchForm);

        return new Response($this->twig->render('{yourTemplate}', [
            'translations' => $pagerFanta,
            'resources' => $pagerFanta,
            'searchForm' => $searchForm->createView(),
        ]));
    }
}
```

And then create a template displaying your translations.
Recommended way is to save translations via an AJAX and use the `SaveTranslationsAction` class to save them.
