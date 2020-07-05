<?php


namespace App\Repositories;


use App\Models\Block;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class BlockRepository
{
    public static function create(Collection $block)
    {
        return Block::create([
            'height' => $block->get('height'),
            'hash' => $block->get('hash'),
            'confirmations' => $block->get('confirmations'),
            'strippedsize' => $block->get('strippedsize'),
            'validated' => $block->get('validated'),
            'size' => $block->get('size'),
            'weight' => $block->get('weight'),
            'version' => $block->get('version'),
            'versionHex' => $block->get('versionHex'),
            'merkleroot' => $block->get('merkleroot'),
            'witness_version' => $block->get('witness_version'),
            'witness_versionHex' => $block->get('witness_versionHex'),
            'witness_time' => $block->get('witness_time'),
            'pow_time' => $block->get('pow_time'),
            'witness_merkleroot' => $block->get('witness_merkleroot'),
            'time' => new Carbon($block->get('time')),
            'nonce' => $block->get('nonce'),
            'pre_nonce' => $block->get('pre_nonce'),
            'post_nonce' => $block->get('post_nonce'),
            'bits' => $block->get('bits'),
            'difficulty' => $block->get('difficulty'),
            'chainwork' => $block->get('chainwork'),
            'previousblockhash' => $block->get('previousblockhash'),
            'created_at' => new Carbon($block->get('mediantime'))
        ]);
    }
}
