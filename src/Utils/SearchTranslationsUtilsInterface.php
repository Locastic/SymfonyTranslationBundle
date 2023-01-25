<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Utils;

use Locastic\SymfonyTranslationBundle\Model\SearchTranslation;
use Pagerfanta\PagerfantaInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface SearchTranslationsUtilsInterface
{
    public function searchTranslationsFromRequest(
        Request $request,
        SearchTranslation $search,
        FormInterface $searchForm
    ): PagerfantaInterface;
}
