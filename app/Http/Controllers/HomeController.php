<?php

namespace App\Http\Controllers;

use App\Repositories\BlockRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function __construct(
        private BlockRepository $blockRepository,
        private TransactionRepository $transactionRepository
    ) {}

    public function index()
    {
        return view('pages.home', [
            'blocks' => $this->blockRepository->index()->cursorPaginate(),
            'blockHeight' => $this->blockRepository->getCurrentHeight(),
            'hashrate' => Cache::get('hashrate') ?? 'unknown',
            'difficulty' => Cache::get('difficulty') ?? 'unknown',
            'transactions24hr' => $this->transactionRepository->countLastTransactions(),
        ]);
    }
}
