<?php

declare(strict_types=1);

namespace spec\App\Application\Command;

use App\Application\Command\SetAvailableCoinsCommand;
use App\Application\Command\SetAvailableCoinsCommandHandler;
use App\Domain\Exception\InvalidCoinException;
use App\Domain\Model\Coin;
use App\Domain\Model\VendingMachine;
use App\Domain\Service\VendingMachineRepository;
use PhpSpec\ObjectBehavior;

class SetAvailableCoinsCommandHandlerSpec extends ObjectBehavior
{
    public function let(VendingMachineRepository $vendingMachineRepository)
    {
        $this->beConstructedWith($vendingMachineRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SetAvailableCoinsCommandHandler::class);
    }

    public function it_should_set_available_change(
        VendingMachineRepository $vendingMachineRepository,
        VendingMachine $vendingMachine,
        Coin $coin1,
        Coin $coin2)
    {
        //Given
        $coins    = [1, 0.10];
        $newCoins = [$coin1, $coin2];
        $vendingMachineRepository->getVendingMachine()->willReturn($vendingMachine);

        //Then
        $vendingMachine->createCoins($coins)->willReturn($newCoins);
        $vendingMachine->setAvailableCoins($newCoins)->shouldBeCalled();

        //When
        $this->__invoke(new SetAvailableCoinsCommand($coins));
    }

    public function it_should_throw_invalid_coin_exception(
        VendingMachineRepository $vendingMachineRepository,
        VendingMachine $vendingMachine)
    {
        //Given
        $coins = [1, 0.50];
        $vendingMachineRepository->getVendingMachine()->willReturn($vendingMachine);

        //Then
        $vendingMachine->createCoins($coins)->willThrow(InvalidCoinException::class);

        //Then
        $this->shouldThrow(InvalidCoinException::class)
            //When
            ->during('__invoke', [new SetAvailableCoinsCommand(
                $coins
            )]);
    }
}
