<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class InvalidArgumentsException extends ContextualizedException
{
    public function __construct(string $input)
    {
        parent::__construct('Invalid arguments', [
            'input' => $input,
        ]);
    }
}
