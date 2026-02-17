<?php

namespace App\Domain\ValueObjects;

class DeliveryLogDetails
{
    private ?string $value;

    public function __construct(?string $value)
    {
        $this->value = $value;
    }

    public function value(): ?string
    {
        return $this->value;
    }

    public function isError(): bool
    {
        return $this->value !== null && !empty($this->value);
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
