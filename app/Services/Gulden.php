<?php


namespace App\Services;


use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class Gulden
{
    /**
     * @var Client
     */
    private $client;

    public function __construct($rpcUser, $rpcPassword, $rpcHost)
    {
        $this->client = new Client([
            'base_uri' => $rpcHost,
            'auth' => [$rpcUser, $rpcPassword]
        ]);
    }

    /**
     * Returns the number of blocks in the longest blockchain.
     *
     * @return int
     */
    public function getBlockCount(): int
    {
        $response = $this->client->post('/', [
            'json' => [
                'method' => 'getblockcount'
            ]
        ])->getBody()->getContents();

        return json_decode($response)->result;
    }

    /**
     * Returns hash of block in best-block-chain at height provided.
     *
     * @param int $height
     * @return string
     */
    public function getBlockHash(int $height): string
    {
        $response = $this->client->post('/', [
            'json' => [
                'method' => 'getblockhash',
                'params' => [$height]
            ]
        ])->getBody()->getContents();

        return json_decode($response)->result;
    }

    /**
     * If verbosity is 0, returns a string that is serialized, hex-encoded data for block 'hash'.
     * If verbosity is 1, returns an Object with information about block <hash>.
     * If verbosity is 2, returns an Object with information about block <hash> and information about each transaction.
     *
     * @param $hash
     * @param $verbosity
     * @return Collection
     */
    public function getBlock($hash, $verbosity = 2)
    {
        $response = $this->client->post('/', [
            'json' => [
                'method' => 'getblock',
                'params' => [$hash, $verbosity]
            ]
        ])->getBody()->getContents();

        return collect(json_decode($response)->result);
    }
}
