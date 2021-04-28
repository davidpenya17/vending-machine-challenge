<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class NoStockAvailableException extends ContextualizedException
{
    public function __construct(string $productName, int $stock)
    {
        parent::__construct('No stock available', [
            'productName' => $productName,
            'stock'       => $stock,
        ]);
    }
}
