<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class InsufficientAvailableChangeException extends ContextualizedException
{
    public function __construct(float $productChange, array $availableChange)
    {
        parent::__construct('Insufficient available change to return', [
            'productChange'  => $productChange,
            'availableChane' => $availableChange,
        ]);
    }
}
