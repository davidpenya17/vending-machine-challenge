<?php

declare(strict_types=1);

namespace App\Domain\Model;

class Juice extends Product
{
    public function __construct(int $stock)
    {
        $this->name  = Product::JUICE;
        $this->price = Product::JUICE_PRICE;
        $this->stock = $stock;
    }
}
