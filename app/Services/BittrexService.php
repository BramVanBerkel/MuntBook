<?php


namespace App\Services;


use Carbon\Carbon;
use Carbon\CarbonInterface;
use GuzzleHttp\Client;

class BittrexService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => sprintf('https://api.bittrex.com'),
        ]);
    }

    public function getPrices(CarbonInterface $date)
    {
        if ($date->isToday()) {
            dump('getting recent...');
            /** /recent returns all candles of the last 24hrs  */
            $request = $this->client->get('/v3/markets/NLG-BTC/candles/MINUTE_1/recent');
        } else {
            dump('getting historical...');
            $request = $this->client->get(sprintf('/v3/markets/NLG-BTC/candles/MINUTE_1/historical/%s', $date->format('Y/m/d')));
        }

        $response = $request->getBody()->getContents();

        return collect(json_decode($response));
    }
}
