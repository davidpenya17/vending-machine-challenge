<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Service\VendingMachineService;
use App\Domain\Exception\InsufficientCoinsException;
use App\Domain\Exception\NoStockAvailableException;
use App\Domain\Service\VendingMachineRepository;

class BuyProductCommandHandler implements CommandHandler
{
    private VendingMachineService $vendingMachineService;
    private VendingMachineRepository $vendingMachineRepository;

    public function __construct(
        VendingMachineService $vendingMachineService,
        VendingMachineRepository $vendingMachineRepository)
    {
        $this->vendingMachineService    = $vendingMachineService;
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function __invoke(BuyProductCommand $command): void
    {
        $vendingMachine = $this->vendingMachineRepository->getVendingMachine();
        $product        = $vendingMachine->getProductByName($command->getProductName());

        if (0 === $product->getStock()) {
            throw new NoStockAvailableException($product->getName(), $product->getStock());
        }

        if (array_sum($command->getCoins()) < $product->getPrice()) {
            throw new InsufficientCoinsException($product->getName(), $command->getCoins());
        }

        $this->vendingMachineService->buyProduct($product, $command->getCoins());
    }
}
