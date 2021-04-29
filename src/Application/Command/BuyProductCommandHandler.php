<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Service\VendingMachineRepository;

class BuyProductCommandHandler implements CommandHandler
{
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function __invoke(BuyProductCommand $command): void
    {
        $vendingMachine = $this->vendingMachineRepository->getVendingMachine();
        $product        = $vendingMachine->getProductByName($command->getProductName());

        $vendingMachine->validateCoins($command->getCoins());

        $vendingMachine->buyProduct($product, $command->getCoins());
    }
}
