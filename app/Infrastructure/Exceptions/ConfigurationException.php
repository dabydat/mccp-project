<?php

namespace App\Infrastructure\Exceptions;

class ConfigurationException extends \Exception
{
    public static function missingConfig(string $key): self
    {
        return new self("Configuration error: '{$key}' is not set in environment or config files.");
    }
}
