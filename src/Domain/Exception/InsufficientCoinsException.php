<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class InsufficientCoinsException extends ContextualizedException
{
    public function __construct(string $productName, float $productPrice, float $totalAmountCoins)
    {
        parent::__construct('Insufficient coins', [
            'productName'      => $productName,
            'productPrice'     => $productPrice,
            'totalAmountCoins' => $totalAmountCoins,
        ]);
    }
}
