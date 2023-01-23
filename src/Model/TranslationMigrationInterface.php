<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Model;

use DateTime;
use Sylius\Component\Resource\Model\ResourceInterface;

interface TranslationMigrationInterface extends ResourceInterface
{
    public function getNumber(): ?string;

    public function setNumber(?string $number): void;

    public function getCreatedAt(): ?DateTime;

    public function setCreatedAt(?DateTime $createdAt): void;
}
