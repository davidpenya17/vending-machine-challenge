<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class ContextualizedException extends \Exception
{
    /** @var array<string, string | int | float | null | array<mixed>> */
    private array $context;

    /**
     * @param array<string, string | int | float | null | array<mixed>> $context
     */
    public function __construct(string $message, array $context = [])
    {
        parent::__construct($message);

        $this->context = $context;
    }

    /**
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
