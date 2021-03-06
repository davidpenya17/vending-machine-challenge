<?php

declare(strict_types=1);

namespace App\Application\Command;

class ReturnCoinsCommand implements Command
{
    private array $coins;

    /**
     * @param array<int, int> $ids
     */
    public function __construct(array $coins)
    {
        $this->coins = $coins;
    }

    public function getCoins(): array
    {
        return $this->coins;
    }
}
