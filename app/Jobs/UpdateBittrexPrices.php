<?php

namespace App\Jobs;

use App\Models\Price;
use App\Services\BittrexService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class UpdateBittrexPrices implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Fetch the current MUNT-BTC and BTC-EUR prices, and calculate the MUNT-EUR price, by comparing it to the current BTC-EUR price.
     */
    public function handle(BittrexService $bittrexService)
    {
        $lastDate = Price::query()
                ->where('source', '=', Price::SOURCE_BITTREX)
                ->max('timestamp') ?? Carbon::create(2020, 3, 31);

        foreach (CarbonPeriod::create($lastDate, now())->floorDays() as $date) {
            $muntPrices = $bittrexService->getPrices($date, 'MUNT-BTC');
            $bitcoinPrices = $bittrexService->getPrices($date, 'BTC-EUR');

            foreach (range(0, min($muntPrices->count() - 1, $bitcoinPrices->count() - 1)) as $index) {
                $avgMuntPrice = $this->calculateAverage($muntPrices[$index]);
                $avgBitcoinPrice = $this->calculateAverage($bitcoinPrices[$index]);

                Price::updateOrCreate([
                    'timestamp' => Carbon::parse($muntPrices[$index]['startsAt']),
                ], [
                    'price' => $avgBitcoinPrice * $avgMuntPrice,
                    'source' => Price::SOURCE_BITTREX,
                ]);
            }
        }
    }

    private function calculateAverage(Collection $prices): float
    {
        return $prices->only([
            'open', 'high', 'low', 'close',
        ])->average();
    }
}
