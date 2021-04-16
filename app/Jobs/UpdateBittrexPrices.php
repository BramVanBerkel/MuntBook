<?php

namespace App\Jobs;

use App\Models\Price;
use App\Repositories\PriceRepository;
use App\Services\BittrexService;
use App\Transformers\PriceTransformer;
use Carbon\CarbonPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateBittrexPrices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(
        PriceRepository $priceRepository,
        PriceTransformer $priceTransformer,
        BittrexService $bittrexService)
    {
        $lastDate = Price::where('source', '=', Price::SOURCE_BITTREX)->max('timestamp');

        foreach (CarbonPeriod::create($lastDate, now())->roundDays() as $date) {
            \Log::channel('stderr')->info((string)$date);
            $prices = $priceTransformer->bittrexTransformer($bittrexService->getPrices($date));

            $prices = collect($prices)->recursive();

            $priceRepository->syncPrices($prices, Price::SOURCE_BITTREX);
        }
    }
}
