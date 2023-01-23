<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Transformer;

use Locastic\SymfonyTranslationBundle\Model\Translation;
use Locastic\SymfonyTranslationBundle\Model\TranslationValue;

final class TranslationKeyToTranslationTransformer implements TranslationKeyToTranslationTransformerInterface
{
    public function transformMultiple(array $translationKeys): array
    {
        $translationsList = [];
        $translations = [];
        foreach ($translationKeys as $themeName => $themeData) {
            foreach ($themeData as $domainName => $domainData) {
                foreach ($domainData as $key => $values) {
                    $translationKey = $domainName . '.' . $key;
                    if (!\array_key_exists($translationKey, $translationsList)) {
                        $translation = new Translation();
                        $translation->setDomainName($domainName);
                        $translation->setKey($key);

                        $translationsList[$translationKey] = $translation;

                        $translations[] = $translation;
                    }

                    $translation = $translationsList[$translationKey];

                    foreach ($values as $localeCode => $value) {
                        $translationValue = new TranslationValue();
                        $translationValue->setLocaleCode($localeCode);
                        $translationValue->setTheme($themeName);
                        $translationValue->setValue('' . $value);

                        $translation->addValue($translationValue);
                    }
                }
            }
        }

        return $translations;
    }
}
