<?php

namespace App\Http\Resources;

use App\DataObjects\PriceData;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @mixin PriceData */
class PriceCollection extends ResourceCollection
{
    public static $wrap;

    public function toArray($request)
    {
        return PriceResource::collection($this->collection);
    }
}
