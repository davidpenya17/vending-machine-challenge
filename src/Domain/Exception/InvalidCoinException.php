<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class InvalidCoinException extends ContextualizedException
{
    public function __construct(float $coin)
    {
        parent::__construct('Invalid coin', [
            'coin' => $coin,
        ]);
    }
}
