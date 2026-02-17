<?php

namespace App\Domain\Services;

use App\Domain\Entities\Message;

interface NotificationChannel
{
    public function send(Message $message): void;
    public function getName(): string;
}
