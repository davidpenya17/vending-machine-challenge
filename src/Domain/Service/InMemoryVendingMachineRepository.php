<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Model\VendingMachine;

class InMemoryVendingMachineRepository implements VendingMachineRepository
{
    private ?VendingMachine $vendingMachine = null;

    public function getVendingMachine(): VendingMachine
    {
        if (null === $this->vendingMachine) {
            $this->vendingMachine = new VendingMachine();
        }

        return $this->vendingMachine;
    }
}
