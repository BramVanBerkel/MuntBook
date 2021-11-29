<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PriceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'x' => $this->time,
            'y' => $this->price,
        ];
    }
}
