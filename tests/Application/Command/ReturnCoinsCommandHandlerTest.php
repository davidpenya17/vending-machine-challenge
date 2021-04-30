<?php

declare(strict_types=1);

namespace App\Tests\Application\Command;

use App\Application\Command\ReturnCoinsCommand;
use App\Application\Command\ReturnCoinsCommandHandler;
use App\Application\Query\GetLastCoinsQuery;
use App\Domain\Exception\InvalidCoinException;
use App\Domain\Model\Coin;
use App\Domain\Model\VendingMachine;
use App\Domain\Service\VendingMachineRepository;
use PHPUnit\Framework\TestCase;

class ReturnCoinsCommandHandlerTest extends TestCase
{

    private $vendingMachineRepositoryMock;

    protected function setUp(): void
    {
        $this->vendingMachineRepositoryMock = $this->createMock(VendingMachineRepository::class);
    }

    public function testThrowInvalidCoin(): void
    {
        //Given
        $vendingMachine = new VendingMachine();
        $coins = [1, 2];

        //When
        $this->vendingMachineRepositoryMock->method('getVendingMachine')->willReturn($vendingMachine);
        $this->expectException(InvalidCoinException::class);

        //Then
        $handler = new ReturnCoinsCommandHandler($this->vendingMachineRepositoryMock);
        $command = new ReturnCoinsCommand(
            $coins
        );
        $handler->__invoke($command);
    }

    public function testReturnCoins(): void
    {
        //Given
        $vendingMachine = new VendingMachine();
        $coins = [1, 0.25];
        $coin1 = new Coin(1);
        $coin2 = new Coin(0.25);
        $returnCoins = [$coin1, $coin2];

        //When
        $this->vendingMachineRepositoryMock->method('getVendingMachine')->willReturn($vendingMachine);

        //Then
        $handler = new ReturnCoinsCommandHandler($this->vendingMachineRepositoryMock);
        $command = new ReturnCoinsCommand(
            $coins
        );
        $handler->__invoke($command);

        $this->assertEquals($vendingMachine->getLastCoins(), $returnCoins);
    }
}
