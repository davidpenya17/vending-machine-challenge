<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Service\VendingMachineRepository;

class SetProductStockCommandHandler implements CommandHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function __invoke(SetProductStockCommand $command): void
    {
        $vendingMachine = $this->vendingMachineRepository->getVendingMachine();
        $product        = $vendingMachine->getProductByName($command->getProductName());

        $product->setStock($command->getStock());
    }
}
