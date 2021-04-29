<?php

declare(strict_types=1);

namespace App\Application\Command;

class SetProductStockCommand implements Command
{
    private string $productName;
    private int $stock;

    /**
     * @param array<int, int> $ids
     */
    public function __construct(string $productName, int $stock)
    {
        $this->productName = $productName;
        $this->stock       = $stock;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getStock(): int
    {
        return $this->stock;
    }
}
