<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Provider;

use Symfony\Component\Finder\Finder;
use function array_key_exists;
use function explode;
use function in_array;

final class TranslationDomainsProvider implements TranslationDomainsProviderInterface
{
    public function toArray(string $translationsDirectory): array
    {
        $domains = [];
        $finder = new Finder();
        foreach ($finder->in($translationsDirectory) as $item) {
            $itemName = $item->getFilename();
            $explodedName = explode('.', $itemName);
            if (!array_key_exists(0, $explodedName) || $explodedName[0] === $itemName) {
                continue;
            }

            $domainName = $explodedName[0];
            if (!in_array($domainName, $domains)) {
                $domains[] = $domainName;
            }
        }

        return $domains;
    }
}
