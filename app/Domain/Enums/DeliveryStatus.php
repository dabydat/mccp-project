<?php

namespace App\Domain\Enums;

enum DeliveryStatus: string
{
    case SUCCESS = 'success';
    case FAILED = 'failed';
    case PENDING = 'pending';
}
