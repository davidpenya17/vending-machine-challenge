<?php

declare(strict_types=1);

namespace App\Domain\Model;

class Water extends Product
{
    public function __construct(int $stock)
    {
        $this->name  = Product::WATER;
        $this->price = Product::WATER_PRICE;
        $this->stock = $stock;
    }
}
