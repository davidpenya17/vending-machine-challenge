<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Model\VendingMachine;

class InMemoryVendingMachineRepository implements VendingMachineRepository
{
    private VendingMachine $vendingMachine;

    public function __construct(VendingMachine $vendingMachine)
    {
        $this->vendingMachine = $vendingMachine;
    }

    public function getVendingMachine(): VendingMachine
    {
        return $this->vendingMachine;
    }
}
