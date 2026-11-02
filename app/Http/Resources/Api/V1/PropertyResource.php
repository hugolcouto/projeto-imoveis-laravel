<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'valueInReal' => round(($this->valueInCents / 100), 2),
            'description' => $this->description,
            'amenities' => $this->amenities,
            'location' => $this->location,
            'transactionType' => $this->transactionType
        ];
    }
}
