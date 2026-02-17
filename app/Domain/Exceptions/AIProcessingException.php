<?php

namespace App\Domain\Exceptions;

class AIProcessingException extends \Exception
{
    public static function failedToGenerateSummary(string $reason): self
    {
        return new self("AI Processing failed: {$reason}");
    }
}
