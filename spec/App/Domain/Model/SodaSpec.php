<?php

declare(strict_types=1);

namespace spec\App\Domain\Model;

use App\Domain\Exception\InsufficientCoinsException;
use App\Domain\Exception\NoStockAvailableException;
use App\Domain\Model\Coin;
use App\Domain\Model\Soda;
use PhpSpec\ObjectBehavior;

class SodaSpec extends ObjectBehavior
{
    const SOME_STOCK = 10;

    public function let()
    {
        $this->beConstructedWith(
            static::SOME_STOCK
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Soda::class);
    }

    public function it_should_set_stock()
    {
        //Given
        $stock = 12;

        //When
        $this->setStock($stock);

        //Then
        $this->getStock()->shouldReturn($stock);
    }

    public function it_should_remove_product_from_stock()
    {
        //Given
        $newStock = 9;

        //When
        $this->removeProductFromStock();

        //Then
        $this->getStock()->shouldReturn($newStock);
    }

    public function it_should_return_true_when_is_available_to_buy(
        Coin $coin1,
        Coin $coin2
    ) {
        //Given
        $coin1->getValue()->willReturn(1);
        $coin2->getValue()->willReturn(0.25);
        $coins = [$coin1, $coin2, $coin2];

        //then
        $this->isAvailableToBuy($coins)->shouldReturn(true);
    }

    public function it_should_throw_no_stock_available_exception(
        Coin $coin
    ) {
        //Given
        $this->setStock(0);
        $coin->getValue()->willReturn(1);
        $coins = [$coin, $coin];

        //then
        $this->shouldThrow(NoStockAvailableException::class)
            //When
            ->during('isAvailableToBuy', [$coins]);
    }

    public function it_should_throw_no_insufficient_coins_exception(
        Coin $coin
    ) {
        //Given
        $coin->getValue()->willReturn(0.25);
        $coins = [$coin, $coin, $coin, $coin, $coin];

        //then
        $this->shouldThrow(InsufficientCoinsException::class)
            //When
            ->during('isAvailableToBuy', [$coins]);
    }
}
