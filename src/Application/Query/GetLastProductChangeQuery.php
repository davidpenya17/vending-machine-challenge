<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Domain\Service\VendingMachineRepository;

class GetLastProductChangeQuery
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    /**
     * @return array<string, mixed>
     */
    public function getResult(): array
    {
        $vendingMachine = $this->vendingMachineRepository->getVendingMachine();

        return $vendingMachine->getLastProductChange();
    }
}
