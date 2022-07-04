<?php

namespace App\Http\Resources;

use App\DataObjects\PriceData;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin PriceData */
class PriceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'time' => $this->timestamp->getTimestamp(),
            'value' => $this->value,
        ];
    }
}
