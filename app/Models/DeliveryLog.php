<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryLog extends Model
{
    use HasFactory;

    protected $fillable = ['message_id', 'channel', 'status', 'details'];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
