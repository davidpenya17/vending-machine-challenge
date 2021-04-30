<?php

declare(strict_types=1);

namespace spec\App\Domain\Model;

use App\Domain\Model\Coin;
use PhpSpec\ObjectBehavior;

class CoinSpec extends ObjectBehavior
{
    const SOME_COIN_VALUE = 0.10;

    public function let()
    {
        $this->beConstructedWith(
            static::SOME_COIN_VALUE
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Coin::class);
    }
}
