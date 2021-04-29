<?php

declare(strict_types=1);

namespace spec\App\Domain\Model;

use App\Domain\Exception\InsufficientAvailableChangeException;
use App\Domain\Exception\InvalidCoinsException;
use App\Domain\Exception\InvalidProductNameException;
use App\Domain\Model\VendingMachine;
use PhpSpec\ObjectBehavior;

class VendingMachineSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(VendingMachine::class);
    }

    public function it_should_return_juice_product()
    {
        //Given
        $productName = 'JUICE';

        //When
        $product = $this->getProductByName($productName);

        //Then
        $product->getName()->shouldReturn($productName);
    }

    public function it_should_throw_invalid_product_name()
    {
        //Given
        $productName = 'some-invalid-name';

        //When
        $this->shouldThrow(InvalidProductNameException::class)
            //Then
            ->during('getProductByName', [$productName]);
    }

    public function it_should_throw_invalid_coins()
    {
        //Given
        $coins = [1, 2];

        //When
        $this->shouldThrow(InvalidCoinsException::class)
            //Then
            ->during('validateCoins', [$coins]);
    }

    public function it_should_return_correct_change()
    {
        //Given
        $coins  = [1];
        $price  = 0.65;
        $change = [0.25, 0.10];

        //When
        $productChange = $this->calculateProductChange($price, $coins);

        //Then
        $productChange->shouldBe($change);
    }

    public function it_should_throw_insufficient_available_change()
    {
        //Given
        $coins = [1];
        $price = 0.65;

        //When
        $this->setAvailableChange([1, 0.10, 0.05]);

        //Then
        $this->shouldThrow(InsufficientAvailableChangeException::class)
            //Then
            ->during('calculateProductChange', [$price, $coins]);
    }

    public function it_should_remove_coins()
    {
        //Given
        $coins          = [1, 0.25, 0.10];
        $availableCoins = [0.05, 0.05, 0.10, 0.25, 1];

        //When
        $this->removeCoins($coins);

        //Then
        $this->getAvailableChange()->shouldBe($availableCoins);
    }

    public function it_should_add_coins()
    {
        //Given
        $coins          = [1, 0.25];
        $availableCoins = [0.05, 0.05, 0.10, 0.10, 0.25, 0.25, 1, 1, 1, 0.25];

        //When
        $this->addCoins($coins);

        //Then
        $this->getAvailableChange()->shouldBe($availableCoins);
    }

    public function it_should_set_available_change()
    {
        //Given
        $coins = [1, 0.25];

        //When
        $this->setAvailableChange($coins);

        //Then
        $this->getAvailableChange()->shouldBe($coins);
    }

    public function it_should_set_last_product_change()
    {
        //Given
        $productChange = [1, 0.25];

        //When
        $this->setLastProductChange($productChange);

        //Then
        $this->getLastProductChange()->shouldBe($productChange);
    }
}
