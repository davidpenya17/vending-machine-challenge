<?php

declare(strict_types=1);

namespace App\Application\Command;

class SetAvailableCoinsCommand implements Command
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
