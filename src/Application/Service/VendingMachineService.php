<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Model\Product;

interface VendingMachineService
{
    public function validateCoins(array $coins): bool;

    public function buyProduct(Product $product, array $coins): void;

    public function returnCoins(array $coins): string;

    public function service(): string;
}
