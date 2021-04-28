<?php

declare(strict_types=1);

namespace App\Domain\Model;

abstract class Product
{
    const WATER       = 'WATER';
    const JUICE       = 'JUICE';
    const SODA        = 'SODA';
    const WATER_PRICE = 0.65;
    const JUICE_PRICE = 1;
    const SODA_PRICE  = 1.50;

    protected string $name;

    protected float $price;

    protected int $stock;

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    public function removeProductFromStock(): void
    {
        --$this->stock;
    }
}
