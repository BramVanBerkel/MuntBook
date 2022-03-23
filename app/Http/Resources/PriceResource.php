<?php

namespace App\Http\Resources;

use App\DataObjects\PriceData;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin PriceData */
class PriceResource extends JsonResource
{
    public function toArray($request)
    {
        // $this->timestamp->timestamp looks a bit funky, but $this->timestamp is a Carbon instance,
        // and we want to return the timestamp (e.g. 1647820800) of that Carbon instance
        return [
            'time' => $this->timestamp->timestamp,
            'value' => $this->value,
        ];
    }
}
