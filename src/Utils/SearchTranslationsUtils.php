<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Utils;

use Locastic\SymfonyTranslationBundle\Model\SearchTranslation;
use Locastic\SymfonyTranslationBundle\Model\TranslationInterface;
use Locastic\SymfonyTranslationBundle\Provider\TranslationsProviderInterface;
use Locastic\SymfonyTranslationBundle\Transformer\TranslationKeyToTranslationTransformerInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\PagerfantaInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use function array_filter;
use function mb_strpos;

final class SearchTranslationsUtils implements SearchTranslationsUtilsInterface
{
    private TranslationsProviderInterface $translationsProvider;

    private TranslationKeyToTranslationTransformerInterface $translationTransformer;

    private string $localeCode;

    private array $locales;

    public function __construct(
        TranslationsProviderInterface $translationsProvider,
        TranslationKeyToTranslationTransformerInterface $translationTransformer,
        string $localeCode,
        array $locales
    ) {
        $this->translationsProvider = $translationsProvider;
        $this->translationTransformer = $translationTransformer;
        $this->localeCode = $localeCode;
        $this->locales = $locales;
    }

    public function searchTranslationsFromRequest(
        Request $request,
        SearchTranslation $search,
        FormInterface $searchForm
    ): PagerfantaInterface {
        $translations = $this->translationsProvider->getTranslations($this->localeCode, $this->locales);
        $translations = $this->translationsProvider->defineAllKeys($translations, $this->locales);
        $translations = $this->translationTransformer->transformMultiple($translations);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $translations = array_filter($translations, function (TranslationInterface $translation) use ($search): bool {
                if (false !== mb_strpos($translation->getKey(), $search->getSearch())) {
                    return true;
                }
                foreach ($translation->getValues() as $translationValue) {
                    if (false !== mb_strpos($translationValue->getValue(), $search->getSearch())) {
                        return true;
                    }
                }

                return false;
            });
        }

        $adapter = new ArrayAdapter($translations);
        $pagerFanta = new Pagerfanta($adapter);

        if (null !== $search->getSearch() && $pagerFanta->getNbResults() > 0) {
            $pagerFanta->setMaxPerPage($pagerFanta->getNbResults());
            $request->query->remove('page');
        } else {
            $pagerFanta->setMaxPerPage(50);
        }
        $pagerFanta->setCurrentPage((int) $request->query->get('page', 1));

        return $pagerFanta;
    }
}
