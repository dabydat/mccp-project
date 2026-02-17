<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\DeliveryLog;
use App\Domain\ValueObjects\Identity;

interface DeliveryLogRepository
{
    public function save(DeliveryLog $log): DeliveryLog;
    public function findByMessageId(Identity $messageId): array;
}
