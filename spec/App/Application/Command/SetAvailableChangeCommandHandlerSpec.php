<?php

declare(strict_types=1);

namespace spec\App\Application\Command;

use App\Application\Command\SetAvailableChangeCommand;
use App\Application\Command\SetAvailableChangeCommandHandler;
use App\Domain\Exception\InvalidCoinsException;
use App\Domain\Model\VendingMachine;
use App\Domain\Service\VendingMachineRepository;
use PhpSpec\ObjectBehavior;

class SetAvailableChangeCommandHandlerSpec extends ObjectBehavior
{
    public function let(VendingMachineRepository $vendingMachineRepository)
    {
        $this->beConstructedWith($vendingMachineRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SetAvailableChangeCommandHandler::class);
    }

    public function it_should_set_available_change(
        VendingMachineRepository $vendingMachineRepository,
        VendingMachine $vendingMachine)
    {
        //Given
        $coins = [1, 1, 0.25, 0.10];
        $vendingMachineRepository->getVendingMachine()->willReturn($vendingMachine);

        //Then
        $vendingMachine->validateCoins($coins)->shouldBeCalled();
        $vendingMachine->setAvailableChange($coins)->shouldBeCalled();

        //When
        $this->__invoke(new SetAvailableChangeCommand($coins));
    }

    public function it_should_throw_invalid_coins(
        VendingMachineRepository $vendingMachineRepository,
        VendingMachine $vendingMachine)
    {
        //Given
        $coins = [1, 2, 0.25, 0.10];
        $vendingMachineRepository->getVendingMachine()->willReturn($vendingMachine);

        //Then
        $vendingMachine->validateCoins($coins)->willThrow(InvalidCoinsException::class);

        //Then
        $this->shouldThrow(InvalidCoinsException::class)
            //When
            ->during('__invoke', [new SetAvailableChangeCommand(
                $coins
            )]);
    }
}
