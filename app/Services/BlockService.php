<?php


namespace App\Services;


use App\Models\Block;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class BlockService
{
    public function __construct(private GuldenService $guldenService) {}

    public function saveBlock(Collection $data): Block
    {
        $witness_time = $data->get('witness_time') !== 0 ? new Carbon($data->get('witness_time')) : null;
        $witness_merkleroot = $data->get('witness_merkleroot') !== Block::EMPTY_WITNESS_MERLKEROOT ? $data->get('witness_merkleroot') : null;
        $witness_version = $data->get('witness_version') !== 0 ? $data->get('witness_version') : null;

        //convert timestamps to current timezone
        $medianTime = $this->getInCurrentTimezone($data->get('mediantime'));
        $time = $this->getInCurrentTimezone($data->get('time'));
        $powTime = $this->getInCurrentTimezone($data->get('pow_time'));
        $hashRate = $this->guldenService->getNetworkHashrate(height: $data->get('height'));

        return Block::updateOrCreate([
            'height' => $data->get('height'),
        ],[
            'hash' => $data->get('hash'),
            'confirmations' => $data->get('confirmations'),
            'strippedsize' => $data->get('strippedsize'),
            'validated' => $data->get('validated'),
            'size' => $data->get('size'),
            'weight' => $data->get('weight'),
            'version' => $data->get('version'),
            'merkleroot' => $data->get('merkleroot'),
            'witness_version' => $witness_version,
            'witness_time' => $witness_time,
            'pow_time' => $powTime,
            'witness_merkleroot' => $witness_merkleroot,
            'time' => $time,
            'nonce' => $data->get('nonce'),
            'pre_nonce' => $data->get('pre_nonce'),
            'post_nonce' => $data->get('post_nonce'),
            'bits' => $data->get('bits'),
            'difficulty' => $data->get('difficulty'),
            'hashrate' => $hashRate,
            'chainwork' => $this->getChainWork($data->get('chainwork')),
            'previousblockhash' => $data->get('previousblockhash'),
            'created_at' => $medianTime,
        ]);
    }

    /**
     * Convert chainwork to gigahashes. We're using GMP because the chainwork decimal number is too long for php
     *
     * @param string $chainwork
     * @return int
     */
    private function getChainwork(string $chainwork): int
    {
        $chainwork = gmp_div(gmp_hexdec($chainwork), gmp_init(1000000000));

        return gmp_intval($chainwork);
    }

    private function getInCurrentTimezone(int $time): Carbon
    {
        $time = new Carbon($time);
        $time->setTimezone(date_default_timezone_get());

        return $time;
    }
}
