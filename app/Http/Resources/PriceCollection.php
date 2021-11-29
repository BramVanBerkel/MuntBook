<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PriceCollection extends ResourceCollection
{
    public static $wrap = null;

    public function toArray($request)
    {
        return PriceResource::collection($this->collection);
    }
}
