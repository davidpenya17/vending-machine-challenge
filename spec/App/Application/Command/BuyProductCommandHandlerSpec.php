<?php

declare(strict_types=1);

namespace spec\App\Application\Command;

use App\Application\Command\BuyProductCommand;
use App\Application\Command\BuyProductCommandHandler;
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
    public function let(VendingMachineRepository $vendingMachineRepository)
    {
        $this->beConstructedWith($vendingMachineRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(BuyProductCommandHandler::class);
    }

    public function it_should_buy_product(
        VendingMachineRepository $vendingMachineRepository,
        VendingMachine $vendingMachine,
        Product $product)
    {
        //Given
        $productName = 'WATER';
        $coins       = [1];
        $vendingMachineRepository->getVendingMachine()->willReturn($vendingMachine);
        $vendingMachine->getProductByName($productName)->willReturn($product);
        $vendingMachine->validateCoins($coins)->shouldBeCalled();

        //Then
        $vendingMachine->buyProduct($product, $coins)->shouldBeCalled();

        //When
        $this->__invoke(new BuyProductCommand($productName, $coins));
    }

    public function it_should_throw_invalid_product_name(
        VendingMachineRepository $vendingMachineRepository,
        VendingMachine $vendingMachine)
    {
        //Given
        $productName = 'TEST';
        $coins       = [1];
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
        $coins       = [1];
        $vendingMachineRepository->getVendingMachine()->willReturn($vendingMachine);
        $vendingMachine->getProductByName($productName)->willReturn($product);
        $vendingMachine->validateCoins($coins)->shouldBeCalled();
        $vendingMachine->buyProduct($product, $coins)->willThrow(NoStockAvailableException::class);

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
        $coins       = [0.25, 0.25, 0.25];
        $vendingMachineRepository->getVendingMachine()->willReturn($vendingMachine);
        $vendingMachine->getProductByName($productName)->willReturn($product);
        $vendingMachine->validateCoins($coins)->shouldBeCalled();
        $vendingMachine->buyProduct($product, $coins)->willThrow(InsufficientCoinsException::class);

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
        VendingMachine $vendingMachine,
        Product $product)
    {
        //Given
        $vendingMachine->setAvailableChange([1, 0.25, 0.05]);
        $productName = 'WATER';
        $coins       = [1];
        $vendingMachineRepository->getVendingMachine()->willReturn($vendingMachine);
        $vendingMachine->getProductByName($productName)->willReturn($product);
        $vendingMachine->validateCoins($coins)->shouldBeCalled();

        //When
        $vendingMachine->buyProduct($product, $coins)->willThrow(InsufficientAvailableChangeException::class);

        //Then
        $this->shouldThrow(InsufficientAvailableChangeException::class)
            //When
            ->during('__invoke', [new BuyProductCommand(
                $productName,
                $coins
            )]);
    }
}
