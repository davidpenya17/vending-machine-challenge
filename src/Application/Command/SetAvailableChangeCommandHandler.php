<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Service\VendingMachineRepository;

class SetAvailableChangeCommandHandler implements CommandHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function __invoke(SetAvailableChangeCommand $command): void
    {
        $vendingMachine = $this->vendingMachineRepository->getVendingMachine();

        $vendingMachine->validateCoins($command->getCoins());

        $vendingMachine->setAvailableChange($command->getCoins());
    }
}
