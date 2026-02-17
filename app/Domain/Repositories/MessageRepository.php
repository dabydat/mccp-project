<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Message;
use App\Domain\ValueObjects\Identity;

interface MessageRepository
{
    public function save(Message $message): Message;
    public function findById(Identity $id): ?Message;
    public function getAll(): array;
}
