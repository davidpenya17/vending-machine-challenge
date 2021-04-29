<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Service\VendingMachineRepository;

class SetAvailableCoinsCommandHandler implements CommandHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function __invoke(SetAvailableCoinsCommand $command): void
    {
        $vendingMachine = $this->vendingMachineRepository->getVendingMachine();

        $newCoins = $vendingMachine->createCoins($command->getCoins());
        $vendingMachine->setAvailableCoins($newCoins);
    }
}
