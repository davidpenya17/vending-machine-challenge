<?php

declare(strict_types=1);

namespace spec\App\Application\Command;

use App\Application\Command\SetProductStockCommand;
use App\Application\Command\SetProductStockCommandHandler;
use App\Domain\Exception\InvalidProductNameException;
use App\Domain\Model\Product;
use App\Domain\Model\VendingMachine;
use App\Domain\Service\VendingMachineRepository;
use PhpSpec\ObjectBehavior;

class SetProductStockCommandHandlerSpec extends ObjectBehavior
{
    public function let(VendingMachineRepository $vendingMachineRepository)
    {
        $this->beConstructedWith($vendingMachineRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SetProductStockCommandHandler::class);
    }

    public function it_should_set_product_stock(
        VendingMachineRepository $vendingMachineRepository,
        VendingMachine $vendingMachine,
        Product $product)
    {
        //Given
        $productName = 'WATER';
        $stock       = 20;
        $vendingMachineRepository->getVendingMachine()->willReturn($vendingMachine);
        $vendingMachine->getProductByName($productName)->willReturn($product);

        //Then
        $product->setStock($stock)->shouldBeCalled();

        //When
        $this->__invoke(new SetProductStockCommand($productName, $stock));
    }

    public function it_should_throw_invalid_product_name(
        VendingMachineRepository $vendingMachineRepository,
        VendingMachine $vendingMachine)
    {
        //Given
        $productName = 'TEST';
        $stock       = 20;
        $vendingMachineRepository->getVendingMachine()->willReturn($vendingMachine);
        $vendingMachine->getProductByName($productName)->willThrow(InvalidProductNameException::class);

        //Then
        $this->shouldThrow(InvalidProductNameException::class)
            //When
            ->during('__invoke', [new SetProductStockCommand(
                $productName,
                $stock
            )]);
    }
}
