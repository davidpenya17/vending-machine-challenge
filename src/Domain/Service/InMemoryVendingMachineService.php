<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Application\Service\VendingMachineService;
use App\Domain\Model\Product;
use App\Domain\Model\VendingMachine;

class InMemoryVendingMachineService implements VendingMachineService
{
    private VendingMachine $vendingMachine;

    public function __construct(VendingMachine $vendingMachine)
    {
        $this->vendingMachine = $vendingMachine;
    }

    public function validateCoins(array $coins): bool
    {
        // TODO: Implement validateCoins() method.
    }

    public function buyProduct(Product $product, array $coins): void
    {
        $this->vendingMachine->buyProduct($product, $coins);
    }

    public function returnCoins(array $coins): string
    {
        // TODO: Implement returnCoins() method.
    }

    public function service(): string
    {
        // TODO: Implement service() method.
    }
}
