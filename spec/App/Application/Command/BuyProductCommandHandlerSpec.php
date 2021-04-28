<?php

declare(strict_types=1);

namespace spec\App\Application\Command;

use App\Application\Command\BuyProductCommand;
use App\Application\Command\BuyProductCommandHandler;
use App\Application\Service\VendingMachineService;
use App\Domain\Exception\InsufficientAvailableChangeException;
use App\Domain\Exception\InsufficientCoinsException;
use App\Domain\Exception\InvalidProductNameException;
use App\Domain\Exception\NoStockAvailableException;
use App\Domain\Model\Product;
use App\Domain\Model\VendingMachine;
use App\Domain\Service\VendingMachineRepository;
use PhpSpec\ObjectBehavior;

class BuyProductCommandHandlerSpec extends ObjectBehavior
{
    public function let(VendingMachineService $vendingMachineService, VendingMachineRepository $vendingMachineRepository)
    {
        $this->beConstructedWith($vendingMachineService, $vendingMachineRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(BuyProductCommandHandler::class);
    }

    public function it_should_buy_product(
        VendingMachineRepository $vendingMachineRepository,
        VendingMachineService $vendingMachineService,
        VendingMachine $vendingMachine,
        Product $product)
    {
        //Given
        $productName = 'WATER';
        $coins = [1];
        $stock = 10;
        $price = 1;
        $vendingMachineRepository->getVendingMachine()->willReturn($vendingMachine);
        $vendingMachine->getProductByName($productName)->willReturn($product);
        $product->getStock()->willReturn($stock);
        $product->getPrice()->willReturn($price);
        $product->getName()->willReturn($productName);

        //Then
        $vendingMachineService->buyProduct($product, $coins)->shouldBeCalled();

        //When
        $this->__invoke(new BuyProductCommand($productName, $coins));
    }

    public function it_should_throw_invalid_product_name(
        VendingMachineRepository $vendingMachineRepository,
        VendingMachine $vendingMachine)
    {
        //Given
        $productName = 'TEST';
        $coins = [1];
        $vendingMachineRepository->getVendingMachine()->willReturn($vendingMachine);
        $vendingMachine->getProductByName($productName)->willThrow(InvalidProductNameException::class);

        //Then
        $this->shouldThrow(InvalidProductNameException::class)
            //When
            ->during('__invoke', [new BuyProductCommand(
                $productName,
                $coins
            )]);
    }

    public function it_should_throw_no_stock_available(
        VendingMachineRepository $vendingMachineRepository,
        VendingMachine $vendingMachine,
        Product $product)
    {
        //Given
        $productName = 'WATER';
        $coins = [1];
        $stock = 0;
        $vendingMachineRepository->getVendingMachine()->willReturn($vendingMachine);
        $vendingMachine->getProductByName($productName)->willReturn($product);
        $product->getStock()->willReturn($stock);
        $product->getName()->willReturn($productName);

        //Then
        $this->shouldThrow(NoStockAvailableException::class)
            //When
            ->during('__invoke', [new BuyProductCommand(
                $productName,
                $coins
            )]);
    }

    public function it_should_throw_insufficient_coins(
        VendingMachineRepository $vendingMachineRepository,
        VendingMachine $vendingMachine,
        Product $product)
    {
        //Given
        $productName = 'WATER';
        $coins = [0.25, 0.25, 0.25];
        $stock = 10;
        $price = 1;
        $vendingMachineRepository->getVendingMachine()->willReturn($vendingMachine);
        $vendingMachine->getProductByName($productName)->willReturn($product);
        $product->getStock()->willReturn($stock);
        $product->getName()->willReturn($productName);
        $product->getPrice()->willReturn($price);

        //Then
        $this->shouldThrow(InsufficientCoinsException::class)
            //When
            ->during('__invoke', [new BuyProductCommand(
                $productName,
                $coins
            )]);
    }

    public function it_should_throw_insufficient_available_change(
        VendingMachineRepository $vendingMachineRepository,
        VendingMachineService $vendingMachineService,
        VendingMachine $vendingMachine,
        Product $product)
    {
        //Given
        $vendingMachine->setAvailableChange([1, 0.25, 0.05]);
        $productName = 'WATER';
        $coins = [1];
        $stock = 10;
        $price = 1;
        $vendingMachineRepository->getVendingMachine()->willReturn($vendingMachine);
        $vendingMachine->getProductByName($productName)->willReturn($product);
        $product->getStock()->willReturn($stock);
        $product->getName()->willReturn($productName);
        $product->getPrice()->willReturn($price);

        //When
        $vendingMachineService->buyProduct($product, $coins)->willThrow(InsufficientAvailableChangeException::class);

        //Then
        $this->shouldThrow(InsufficientAvailableChangeException::class)
            //When
            ->during('__invoke', [new BuyProductCommand(
                $productName,
                $coins
            )]);
    }
}
