<?php
namespace App\Domain\Service;

use App\Application\Service\VendingMachineService;

class InMemoryVendingMachineService implements VendingMachineService
{

    public function validateCoins(array $coins): bool
    {
        // TODO: Implement validateCoins() method.
    }

    public function buyItem(string $productName, array $coins): array
    {
        // TODO: Implement buyItem() method.
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