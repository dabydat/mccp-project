<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

class MessageTitle
{
    private string $value;

    public function __construct(string $value)
    {
        if (empty(trim($value))) {
            throw new InvalidArgumentException("Title cannot be empty.");
        }

        if (strlen($value) > 255) {
            throw new InvalidArgumentException("Title cannot exceed 255 characters.");
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
