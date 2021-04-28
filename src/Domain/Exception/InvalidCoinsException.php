<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class InvalidCoinsException extends ContextualizedException
{
    public function __construct(array $coins)
    {
        parent::__construct('Invalid coins', [
            'coins' => implode(', ', $coins),
        ]);
    }
}
