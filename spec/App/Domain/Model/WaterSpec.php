<?php

declare(strict_types=1);

namespace spec\App\Domain\Model;

use App\Domain\Model\Water;
use PhpSpec\ObjectBehavior;

class WaterSpec extends ObjectBehavior
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
        $this->shouldHaveType(Water::class);
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
}
