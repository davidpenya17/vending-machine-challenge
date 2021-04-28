<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Model\VendingMachine;

interface VendingMachineRepository
{
    public function getVendingMachine(): VendingMachine;
}
