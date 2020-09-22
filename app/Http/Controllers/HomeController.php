<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $blocks = Block::orderBy('height', 'desc')->simplePaginate(20);

        $hashrate = Cache::get('hashrate') ?? 'unknown';

        $difficulty = Cache::get('difficulty') ?? 'unknown';

        return view('layouts.pages.index', [
            'blocks' => $blocks,
            'hashrate' => $hashrate,
            'difficulty' => $difficulty,
        ]);
    }
}
