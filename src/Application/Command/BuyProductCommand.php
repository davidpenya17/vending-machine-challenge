<?php

declare(strict_types=1);

namespace App\Application\Command;

class BuyProductCommand implements Command
{
    private string $productName;
    private array $coins;

    /**
     * @param array<int, int> $ids
     */
    public function __construct(string $productName, array $coins)
    {
        $this->productName = $productName;
        $this->coins       = $coins;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getCoins(): array
    {
        return $this->coins;
    }
}
