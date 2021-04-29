<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Service\VendingMachineRepository;

class ReturnCoinsCommandHandler implements CommandHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function __invoke(ReturnCoinsCommand $command): void
    {
        $vendingMachine = $this->vendingMachineRepository->getVendingMachine();

        $newCoins = $vendingMachine->createCoins($command->getCoins());
        $vendingMachine->setLastCoins($newCoins);
    }
}
