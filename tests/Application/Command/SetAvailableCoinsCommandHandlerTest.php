<?php

declare(strict_types=1);

namespace App\Tests\Application\Command;

use App\Application\Command\SetAvailableCoinsCommand;
use App\Application\Command\SetAvailableCoinsCommandHandler;
use App\Domain\Exception\InvalidCoinException;
use App\Domain\Model\Coin;
use App\Domain\Model\VendingMachine;
use App\Domain\Service\VendingMachineRepository;
use PHPUnit\Framework\TestCase;

class SetAvailableCoinsCommandHandlerTest extends TestCase
{
    private $vendingMachineRepositoryMock;

    protected function setUp(): void
    {
        $this->vendingMachineRepositoryMock = $this->createMock(VendingMachineRepository::class);
    }

    public function testThrowInvalidCoinException(): void
    {
        //Given
        $vendingMachine = new VendingMachine();
        $coins          = [1, 2];

        //When
        $this->vendingMachineRepositoryMock->method('getVendingMachine')->willReturn($vendingMachine);
        $this->expectException(InvalidCoinException::class);

        //Then
        $handler = new SetAvailableCoinsCommandHandler($this->vendingMachineRepositoryMock);
        $command = new SetAvailableCoinsCommand(
            $coins
        );
        $handler->__invoke($command);
    }

    public function testSetAvailableCoins(): void
    {
        //Given
        $vendingMachine = new VendingMachine();
        $coins          = [1, 0.25, 0.10];
        $coin1          = new Coin(1);
        $coin2          = new Coin(0.25);
        $coin3          = new Coin(0.10);
        $availableCoins = [$coin1, $coin2, $coin3];

        //When
        $this->vendingMachineRepositoryMock->method('getVendingMachine')->willReturn($vendingMachine);

        //Then
        $handler = new SetAvailableCoinsCommandHandler($this->vendingMachineRepositoryMock);
        $command = new SetAvailableCoinsCommand(
            $coins
        );
        $handler->__invoke($command);

        $this->assertEquals($vendingMachine->getAvailableCoins(), $availableCoins);
    }
}
