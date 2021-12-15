<?php

namespace App\Http\Controllers;

use App\Repositories\BlockRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private BlockRepository $blockRepository,
        private TransactionRepository $transactionRepository
    ) {}

    public function index(): View
    {
        return view('pages.home', [
            'blocks' => $this->blockRepository->index(),
            'blockHeight' => $this->blockRepository->currentHeight(),
            'hashrate' => Cache::get('hashrate', 'unknown'),
            'difficulty' => Cache::get('difficulty','unknown'),
            'transactions24hr' => $this->transactionRepository->countLastTransactions(),
        ]);
    }
}
