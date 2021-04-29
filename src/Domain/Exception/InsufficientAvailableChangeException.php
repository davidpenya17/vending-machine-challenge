<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class InsufficientAvailableChangeException extends ContextualizedException
{
    public function __construct(float $productChange)
    {
        parent::__construct('Insufficient available change to return', [
            'productChange' => $productChange,
        ]);
    }
}
