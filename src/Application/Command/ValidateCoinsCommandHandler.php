<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Service\VendingMachineService;

class ValidateCoinsCommandHandler implements CommandHandler
{
    private VendingMachineService $vendingMachineService;

    public function __construct(VendingMachineService $vendingMachineService)
    {
        $this->vendingMachineService = $vendingMachineService;
    }

    public function __invoke(ValidateCoinsCommand $command): void
    {
        $this->vendingMachineService->validateCoins($command->getCoins());
    }
}
