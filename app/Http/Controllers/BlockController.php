<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlockController extends Controller
{
    public function index(Block $block): View
    {
        return view('pages.block')->with([
            'previousBlock' => (Block::find($block->height - 1)) ? $block->height - 1 : null,
            'nextBlock' => (Block::find($block->height + 1)) ? $block->height + 1 : null,
            'block' => $block
        ]);
    }
}
