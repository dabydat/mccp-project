<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

class MessageSummary
{
    private string $value;

    public function __construct(string $value)
    {
        if (mb_strlen($value) > 100) {
            throw new InvalidArgumentException("Summary cannot exceed 100 characters.");
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
