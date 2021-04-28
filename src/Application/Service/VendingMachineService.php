<?php
namespace App\Application\Service;

interface VendingMachineService
{
    public function validateCoins(array $coins): bool;

    public function buyItem(string $productName, array $coins): array;

    public function returnCoins(array $coins): string;

    public function service(): string;
}
