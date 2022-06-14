<?php

namespace App\Services;

use Carbon\CarbonInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class BittrexService
{
    private readonly Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.bittrex.com',
        ]);
    }

    public function getPrices(CarbonInterface $date, string $symbol): Collection
    {
        if ($date->isToday()) {
            /** /recent returns all candles of the last 24hrs  */
            $request = $this->client->get(sprintf('/v3/markets/%s/candles/MINUTE_1/recent', $symbol));
        } else {
            $request = $this->client->get(sprintf('/v3/markets/%s/candles/MINUTE_1/historical/%s', $symbol, $date->format('Y/m/d')));
        }

        $response = $request->getBody()->getContents();

        return collect(json_decode($response, null, 512, JSON_THROW_ON_ERROR))->recursive();
    }
}
