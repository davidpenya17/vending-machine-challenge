<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class InvalidProductNameException extends ContextualizedException
{
    public function __construct(string $productName)
    {
        parent::__construct('Invalid product name', [
            'productName' => $productName,
        ]);
    }
}
