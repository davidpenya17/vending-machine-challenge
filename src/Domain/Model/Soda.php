<?php

declare(strict_types=1);

namespace App\Domain\Model;

class Soda extends Product
{
    public function __construct(int $stock)
    {
        $this->name  = Product::SODA;
        $this->price = Product::SODA_PRICE;
        $this->stock = $stock;
    }
}
