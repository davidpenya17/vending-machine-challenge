<?php

declare(strict_types=1);

namespace spec\App\Domain\Model;

use App\Domain\Exception\InsufficientAvailableChangeException;
use App\Domain\Exception\InvalidProductNameException;
use App\Domain\Model\Coin;
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

    public function it_should_return_correct_change(
        Coin $coin
    ) {
        //Given
        $coin->getValue()->willReturn(1);
        $coins = [$coin];
        $price = 0.65;

        //When
        $change = $this->calculateProductChange($price, $coins);

        //Then
        $this->getLastProductChange()->shouldReturn($change);
    }

    public function it_should_throw_insufficient_available_change(
        Coin $coin1,
        Coin $coin2,
        Coin $coin3
    ) {
        //Given
        $coin1->getValue()->willReturn(1);
        $coin2->getValue()->willReturn(0.10);
        $coin3->getValue()->willReturn(0.05);
        $coins          = [$coin1];
        $availableCoins = [$coin1, $coin2, $coin3];
        $price          = 0.65;

        //When
        $this->setAvailableCoins($availableCoins);

        //Then
        $this->shouldThrow(InsufficientAvailableChangeException::class)
            //Then
            ->during('calculateProductChange', [$price, $coins]);
    }

    public function it_should_remove_coins(
        Coin $coin1,
        Coin $coin2,
        Coin $coin3,
        Coin $coin4
    ) {
        //Given
        $coin1->getValue()->willReturn(1);
        $coin2->getValue()->willReturn(0.25);
        $coin3->getValue()->willReturn(0.10);
        $coin4->getValue()->willReturn(0.05);
        $coins          = [$coin1, $coin2];
        $availableCoins = [$coin4, $coin4, $coin3, $coin2, $coin1];
        $this->setAvailableCoins($availableCoins);

        //When
        $this->removeCoins($coins);

        //Then
        $this->getAvailableCoins()->shouldReturn([$coin4, $coin4, $coin3]);
    }

    public function it_should_add_coins(
        Coin $coin1,
        Coin $coin2,
        Coin $coin3,
        Coin $coin4
    ) {
        //Given
        $coin1->getValue()->willReturn(1);
        $coin2->getValue()->willReturn(0.25);
        $coin3->getValue()->willReturn(0.10);
        $coin4->getValue()->willReturn(0.05);
        $coins          = [$coin1, $coin2];
        $availableCoins = [$coin4, $coin3];
        $this->setAvailableCoins($availableCoins);

        //When
        $this->addCoins($coins);

        //Then
        $this->getAvailableCoins()->shouldBe([$coin4, $coin3, $coin1, $coin2]);
    }

    public function it_should_set_available_change(
        Coin $coin1,
        Coin $coin2
    ) {
        //Given
        $coin1->getValue()->willReturn(1);
        $coin2->getValue()->willReturn(0.25);
        $coins = [$coin1, $coin2];

        //When
        $this->setAvailableCoins($coins);

        //Then
        $this->getAvailableCoins()->shouldBe($coins);
    }

    public function it_should_set_last_product_change(
        Coin $coin1,
        Coin $coin2
    ) {
        //Given
        $coin1->getValue()->willReturn(1);
        $coin2->getValue()->willReturn(0.25);
        $productChange = [$coin1, $coin2];

        //When
        $this->setLastProductChange($productChange);

        //Then
        $this->getLastProductChange()->shouldBe($productChange);
    }
}
