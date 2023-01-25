<?php

declare(strict_types=1);

namespace Locastic\SymfonyTranslationBundle\Model;

use DateTime;

interface TranslationMigrationInterface
{
    public function getId(): ?int;

    public function getNumber(): ?string;

    public function setNumber(?string $number): void;

    public function getCreatedAt(): ?DateTime;

    public function setCreatedAt(?DateTime $createdAt): void;
}
