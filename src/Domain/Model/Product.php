<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Exception\InsufficientCoinsException;
use App\Domain\Exception\NoStockAvailableException;

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

    public function isAvailableToBuy(array $coins): bool
    {
        if (0 === $this->getStock()) {
            throw new NoStockAvailableException($this->getName(), $this->getStock());
        }

        $totalAmountCoins = 0;
        foreach ($coins as $coin) {
            $totalAmountCoins += $coin->getValue();
        }
        if ($totalAmountCoins < $this->getPrice()) {
            throw new InsufficientCoinsException($this->getName(), $this->getPrice(), $totalAmountCoins);
        }

        return true;
    }
}
