<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Exception\InvalidCoinException;

class Coin
{
    const FIVE_CENTS        = 0.05;
    const TEN_CENTS         = 0.10;
    const TWENTY_FIVE_CENTS = 0.25;
    const ONE_EURO          = 1;

    private float $value;

    public function __construct(float $value)
    {
        if (!in_array($value, [
            self::ONE_EURO,
            self::TWENTY_FIVE_CENTS,
            self::TEN_CENTS,
            self::FIVE_CENTS, ])) {
            throw new InvalidCoinException($value);
        }

        $this->value = $value;
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
