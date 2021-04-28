<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class InsufficientCoinsException extends ContextualizedException
{
    public function __construct(string $productName, array $coins)
    {
        parent::__construct('Insufficient coins', [
            'productName' => $productName,
            'coins'       => implode(', ', $coins),
        ]);
    }
}
