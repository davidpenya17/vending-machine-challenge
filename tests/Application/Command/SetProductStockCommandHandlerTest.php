<?php

declare(strict_types=1);

namespace App\Tests\Application\Command;

use App\Application\Command\SetProductStockCommand;
use App\Application\Command\SetProductStockCommandHandler;
use App\Domain\Exception\InvalidProductNameException;
use App\Domain\Model\VendingMachine;
use App\Domain\Service\VendingMachineRepository;
use PHPUnit\Framework\TestCase;

class SetProductStockCommandHandlerTest extends TestCase
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
        $stock          = 20;

        //When
        $this->vendingMachineRepositoryMock->method('getVendingMachine')->willReturn($vendingMachine);
        $this->expectException(InvalidProductNameException::class);

        //Then
        $handler = new SetProductStockCommandHandler($this->vendingMachineRepositoryMock);
        $command = new SetProductStockCommand(
            $productName,
            $stock
        );
        $handler->__invoke($command);
    }

    public function testSetProductStock(): void
    {
        //Given
        $vendingMachine = new VendingMachine();
        $productName    = 'WATER';
        $stock          = 20;

        //When
        $this->vendingMachineRepositoryMock->method('getVendingMachine')->willReturn($vendingMachine);

        //Then
        $handler = new SetProductStockCommandHandler($this->vendingMachineRepositoryMock);
        $command = new SetProductStockCommand(
            $productName,
            $stock
        );
        $handler->__invoke($command);

        $this->assertEquals($vendingMachine->getProductByName($productName)->getStock(), $stock);
    }
}
