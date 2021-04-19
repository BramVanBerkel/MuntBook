<?php


namespace App\Repositories;


use App\Models\Block;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class BlockRepository
{
    public function syncBlock(Collection $data): Block
    {
        $witness_time = $data->get('witness_time') !== 0 ? new Carbon($data->get('witness_time')) : null;
        $witness_merkleroot = $data->get('witness_merkleroot') !== Block::EMPTY_WITNESS_MERLKEROOT ? $data->get('witness_merkleroot') : null;
        $witness_version = $data->get('witness_version') === 0 ? null : $data->get('witness_version');

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
            'pow_time' => new Carbon($data->get('pow_time')),
            'witness_merkleroot' => $witness_merkleroot,
            'time' => new Carbon($data->get('time')),
            'nonce' => $data->get('nonce'),
            'pre_nonce' => $data->get('pre_nonce'),
            'post_nonce' => $data->get('post_nonce'),
            'bits' => $data->get('bits'),
            'difficulty' => $data->get('difficulty'),
            'chainwork' => $data->get('chainwork'),
            'previousblockhash' => $data->get('previousblockhash'),
            'created_at' => new Carbon($data->get('mediantime'))
        ]);
    }
}
