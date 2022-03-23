<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Carbon\Carbon;

class MissingBlockController extends Controller
{
    // todo: fix controller
    public function index(int $block)
    {
        $lastBlock = Block::max('height');

        $difference = $block - $lastBlock;

        $seconds = $difference * config('gulden.blocktime');

//        return view('layouts.pages.block-missing', [
//            'block' => $block,
//            'time' => now()->addSeconds($seconds),
//        ]);
    }
}
