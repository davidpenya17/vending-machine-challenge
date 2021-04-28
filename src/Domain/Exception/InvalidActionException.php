<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class InvalidActionException extends ContextualizedException
{
    public function __construct(string $action)
    {
        parent::__construct('Invalid action', [
            'action' => $action,
        ]);
    }
}
