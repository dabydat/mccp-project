<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'title' => $this['title'],
            'summary' => $this['summary'],
            'content' => $this['content'],
            'created_at' => $this['created_at'],
            'delivery_logs' => $this['logs'] ?? []
        ];
    }
}
