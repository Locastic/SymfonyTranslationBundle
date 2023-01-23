<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Model;

use DateTime;

class TranslationMigration implements TranslationMigrationInterface
{
    private ?int $id = null;

    private ?string $number = null;

    private ?DateTime $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): void
    {
        $this->number = $number;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
