<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Model;

class SearchTranslation implements SearchTranslationInterface
{
    private ?string $search = null;

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): void
    {
        $this->search = $search;
    }
}
