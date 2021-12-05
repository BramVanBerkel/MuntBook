<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class PriceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'time' => Carbon::make($this->time)->timestamp,
            'value' => $this->value,
        ];
    }
}
