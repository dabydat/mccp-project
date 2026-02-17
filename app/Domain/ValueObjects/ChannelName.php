<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

class ChannelName
{
    private string $value;
    private const ALLOWED_CHANNELS = ['email', 'slack', 'sms'];

    public function __construct(string $value)
    {
        $value = strtolower($value);
        if (!in_array($value, self::ALLOWED_CHANNELS)) {
            throw new InvalidArgumentException("Invalid channel name: {$value}");
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
