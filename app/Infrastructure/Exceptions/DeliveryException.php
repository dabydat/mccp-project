<?php

namespace App\Infrastructure\Exceptions;

class DeliveryException extends \Exception
{
    public static function channelError(string $channel, string $reason): self
    {
        return new self("Delivery failed for channel '{$channel}': {$reason}");
    }
}
