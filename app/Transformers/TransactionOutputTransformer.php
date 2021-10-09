<?php

namespace App\Transformers;

use App\Models\Vin;
use App\Models\Vout;
use Illuminate\Support\Collection;

class TransactionOutputTransformer
{
    /**
     * @param Collection $collection
     * @return Collection
     * Accepts a collection of Vins and Vouts and transforms it to a collection that is easy to use in the frontend
     */
    public function transform(Collection $collection): Collection
    {
        return $collection->map(function(Vin|Vout $output) {
             return collect([
                 'address' => ($output instanceof Vin) ? $output->address : $output->address->address,
                 'value' => ($output instanceof Vin) ? (float)-$output->value : (float)$output->value,
             ]);
        });
    }
}
