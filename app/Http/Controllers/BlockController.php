<?php

namespace App\Http\Controllers;

use App\Models\Block;

class BlockController extends Controller
{
    public function index(Block $block)
    {
        return view('layouts.pages.block')->with([
            'previousBlock' => (Block::find($block->height - 1)) ? $block->height - 1 : null,
            'nextBlock' => (Block::find($block->height + 1)) ? $block->height + 1 : null,
            'block' => $block
        ]);
    }
}
