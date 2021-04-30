<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Domain\Service\VendingMachineRepository;

class GetProductStockQuery
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    /**
     * @return array<string, mixed>
     */
    public function getResult(string $productName): int
    {
        $vendingMachine = $this->vendingMachineRepository->getVendingMachine();
        $product        = $vendingMachine->getProductByName($productName);

        return $product->getStock();
    }
}
