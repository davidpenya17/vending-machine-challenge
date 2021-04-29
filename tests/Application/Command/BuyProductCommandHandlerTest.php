<?php

declare(strict_types=1);

namespace App\Tests\Application\Command;

use App\Application\Command\BuyProductCommand;
use App\Application\Command\BuyProductCommandHandler;
use App\Domain\Exception\InsufficientAvailableChangeException;
use App\Domain\Exception\InsufficientCoinsException;
use App\Domain\Exception\InvalidCoinsException;
use App\Domain\Exception\InvalidProductNameException;
use App\Domain\Exception\NoStockAvailableException;
use App\Domain\Model\VendingMachine;
use App\Domain\Service\VendingMachineRepository;
use PHPUnit\Framework\TestCase;

class BuyProductCommandHandlerTest extends TestCase
{

    private $vendingMachineRepositoryMock;

    protected function setUp(): void
    {
        $this->vendingMachineRepositoryMock = $this->createMock(VendingMachineRepository::class);
    }

    public function testThrowInvalidProductName(): void
    {
        //Given
        $vendingMachine = new VendingMachine();
        $productName = 'TEST';
        $coins = [1];

        //When
        $this->vendingMachineRepositoryMock->method('getVendingMachine')->willReturn($vendingMachine);
        $this->expectException(InvalidProductNameException::class);

        //Then
        $handler = new BuyProductCommandHandler($this->vendingMachineRepositoryMock);
        $command = new BuyProductCommand(
            $productName,
            $coins
        );
        $handler->__invoke($command);
    }

    public function testThrowInvalidCoins(): void
    {
        //Given
        $vendingMachine = new VendingMachine();
        $productName = 'WATER';
        $coins = [1, 2];

        //When
        $this->vendingMachineRepositoryMock->method('getVendingMachine')->willReturn($vendingMachine);
        $this->expectException(InvalidCoinsException::class);

        //Then
        $handler = new BuyProductCommandHandler($this->vendingMachineRepositoryMock);
        $command = new BuyProductCommand(
            $productName,
            $coins
        );
        $handler->__invoke($command);
    }

    public function testThrowInsufficientAvailableChange(): void
    {
        //Given
        $vendingMachine = new VendingMachine();
        $productName = 'WATER';
        $coins = [1];
        $availableChange = [1, 0.25, 0.05];
        $vendingMachine->setAvailableChange($availableChange);

        //When
        $this->vendingMachineRepositoryMock->method('getVendingMachine')->willReturn($vendingMachine);
        $this->expectException(InsufficientAvailableChangeException::class);

        //Then
        $handler = new BuyProductCommandHandler($this->vendingMachineRepositoryMock);
        $command = new BuyProductCommand(
            $productName,
            $coins
        );
        $handler->__invoke($command);
    }

    public function testThrowNoStockAvailable(): void
    {
        //Given
        $vendingMachine = new VendingMachine();
        $productName = 'WATER';
        $coins = [1];

        //When
        $this->vendingMachineRepositoryMock->method('getVendingMachine')->willReturn($vendingMachine);
        $product = $vendingMachine->getProductByName($productName);
        $product->setStock(0);
        $this->expectException(NoStockAvailableException::class);

        //Then
        $handler = new BuyProductCommandHandler($this->vendingMachineRepositoryMock);
        $command = new BuyProductCommand(
            $productName,
            $coins
        );
        $handler->__invoke($command);
    }

    public function testThrowInsufficientCoins(): void
    {
        //Given
        $vendingMachine = new VendingMachine();
        $productName = 'SODA';
        $coins = [1, 0.25, 0.10, 0.10];

        //When
        $this->vendingMachineRepositoryMock->method('getVendingMachine')->willReturn($vendingMachine);
        $this->expectException(InsufficientCoinsException::class);

        //Then
        $handler = new BuyProductCommandHandler($this->vendingMachineRepositoryMock);
        $command = new BuyProductCommand(
            $productName,
            $coins
        );
        $handler->__invoke($command);
    }
}
