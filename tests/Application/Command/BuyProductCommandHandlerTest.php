<?php

declare(strict_types=1);

namespace App\Tests\Application\Command;

use App\Application\Command\BuyProductCommand;
use App\Application\Command\BuyProductCommandHandler;
use App\Domain\Exception\InsufficientAvailableChangeException;
use App\Domain\Exception\InsufficientCoinsException;
use App\Domain\Exception\InvalidCoinException;
use App\Domain\Exception\InvalidProductNameException;
use App\Domain\Exception\NoStockAvailableException;
use App\Domain\Model\Coin;
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

    public function testThrowInvalidProductNameException(): void
    {
        //Given
        $vendingMachine = new VendingMachine();
        $productName    = 'TEST';
        $coins          = [1];

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

    public function testThrowInvalidCoinException(): void
    {
        //Given
        $vendingMachine = new VendingMachine();
        $productName    = 'WATER';
        $coins          = [1, 2];

        //When
        $this->vendingMachineRepositoryMock->method('getVendingMachine')->willReturn($vendingMachine);
        $this->expectException(InvalidCoinException::class);

        //Then
        $handler = new BuyProductCommandHandler($this->vendingMachineRepositoryMock);
        $command = new BuyProductCommand(
            $productName,
            $coins
        );
        $handler->__invoke($command);
    }

    public function testThrowInsufficientAvailableChangeException(): void
    {
        //Given
        $vendingMachine  = new VendingMachine();
        $productName     = 'WATER';
        $coins           = [1];
        $coin1           = new Coin(1);
        $coin2           = new Coin(0.25);
        $coin3           = new Coin(0.05);
        $availableChange = [$coin1, $coin2, $coin3];
        $vendingMachine->setAvailableCoins($availableChange);

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

    public function testThrowNoStockAvailableException(): void
    {
        //Given
        $vendingMachine = new VendingMachine();
        $productName    = 'WATER';
        $coins          = [1];

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

    public function testThrowInsufficientCoinsException(): void
    {
        //Given
        $vendingMachine = new VendingMachine();
        $productName    = 'SODA';
        $coins          = [1, 0.25, 0.10, 0.10];

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

    public function testBuyProduct(): void
    {
        //Given
        $vendingMachine        = new VendingMachine();
        $productName           = 'WATER';
        $coins                 = [0.25, 0.25, 0.10, 0.10];
        $coin1                 = new Coin(0.05);
        $productStockAvailable = 9;
        $productChange         = [$coin1];

        //When
        $this->vendingMachineRepositoryMock->method('getVendingMachine')->willReturn($vendingMachine);

        //Then
        $handler = new BuyProductCommandHandler($this->vendingMachineRepositoryMock);
        $command = new BuyProductCommand(
            $productName,
            $coins
        );
        $handler->__invoke($command);

        $this->assertEquals($vendingMachine->getProductByName($productName)->getStock(), $productStockAvailable);
        $this->assertEquals($vendingMachine->getLastProductChange(), $productChange);
    }

    public function testBuyThreeWatersAndThrowInsufficientAvailableCoinsException(): void
    {
        //Given
        $vendingMachine = new VendingMachine();
        $productName    = 'WATER';
        $coins          = [1];
        $coin1          = new Coin(0.25);
        $coin2          = new Coin(0.10);
        $productChange  = [$coin1, $coin2];

        //When
        $this->vendingMachineRepositoryMock->method('getVendingMachine')->willReturn($vendingMachine);
        $this->expectException(InsufficientAvailableChangeException::class);

        //Then
        $handler = new BuyProductCommandHandler($this->vendingMachineRepositoryMock);
        $command = new BuyProductCommand(
            $productName,
            $coins
        );

        // Buy first water
        $handler->__invoke($command);
        $this->assertEquals($vendingMachine->getProductByName($productName)->getStock(), 9);
        $this->assertEquals($vendingMachine->getLastProductChange(), $productChange);

        // Buy second water
        $handler->__invoke($command);
        $this->assertEquals($vendingMachine->getProductByName($productName)->getStock(), 8);
        $this->assertEquals($vendingMachine->getLastProductChange(), $productChange);

        // Buy third water
        $handler->__invoke($command);
    }

    public function testBuyTwoSodasAndThrowNoStockAvailableException(): void
    {
        //Given
        $vendingMachine = new VendingMachine();
        $productName    = 'SODA';
        $stock          = 1;
        $coins          = [1, 1];
        $coin1          = new Coin(0.25);
        $productChange  = [$coin1, $coin1];

        //When
        $this->vendingMachineRepositoryMock->method('getVendingMachine')->willReturn($vendingMachine);
        $vendingMachine->getProductByName($productName)->setStock($stock);
        $this->expectException(NoStockAvailableException::class);

        //Then
        $handler = new BuyProductCommandHandler($this->vendingMachineRepositoryMock);
        $command = new BuyProductCommand(
            $productName,
            $coins
        );

        // Buy first soda
        $handler->__invoke($command);
        $this->assertEquals($vendingMachine->getProductByName($productName)->getStock(), 0);
        $this->assertEquals($vendingMachine->getLastProductChange(), $productChange);

        // Buy second soda
        $handler->__invoke($command);
    }
}
