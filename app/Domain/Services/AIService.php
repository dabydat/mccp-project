<?php

namespace App\Domain\Services;

use App\Domain\ValueObjects\MessageSummary;

interface AIService
{
    public function generateSummary(string $content): MessageSummary;
}
