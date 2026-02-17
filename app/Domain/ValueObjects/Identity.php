<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

class Identity
{
    public function __construct(private ?int $value)
    {
        if ($value !== null && $value <= 0) {
            throw new InvalidArgumentException("Identity must be a positive integer.");
        }
    }

    public function value(): ?int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function equals(?Identity $other): bool
    {
        return $other !== null && $this->value === $other->value();
    }
}
