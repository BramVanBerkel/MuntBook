<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Repositories\BlockRepository;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function index(Block $block)
    {
        return view('layouts.pages.block')->with([
            'block' => $block
        ]);
    }
}
