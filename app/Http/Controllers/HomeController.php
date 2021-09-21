<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $blocks = Block::orderBy('height', 'desc')->simplePaginate(10);

        $blockHeight = Block::max('height');

        $hashrate = Cache::get('hashrate') ?? 'unknown';

        $difficulty = Cache::get('difficulty') ?? 'unknown';

        $transactions24hr = Transaction::query()
            ->where('type', '=', Transaction::TYPE_TRANSACTION)
            ->where('created_at', '>', now()->subDay())
            ->count();

        return view('pages.home', [
            'blockHeight' => $blockHeight,
            'hashrate' => $hashrate,
            'difficulty' => $difficulty,
            'blocks' => $blocks,
            'transactions24hr' => $transactions24hr,
        ]);
    }
}
