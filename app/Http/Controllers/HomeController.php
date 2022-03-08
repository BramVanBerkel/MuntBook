<?php

namespace App\Http\Controllers;

use App\Repositories\BlockRepository;
use App\Repositories\PriceRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private BlockRepository $blockRepository,
        private TransactionRepository $transactionRepository,
        private PriceRepository $priceRepository,
    ) {}

    public function __invoke(): View
    {
        return view('pages.home', [
            'blocks' => $this->blockRepository->index(),
            'blockHeight' => $this->blockRepository->currentHeight(),
            'hashrate' => Cache::get('hashrate', 'unknown'),
            'difficulty' => Cache::get('difficulty','unknown'),
            'transactions24hr' => $this->transactionRepository->countLastTransactions(),
            'price' => $this->priceRepository->getCurrentPrice(),
        ]);
    }
}
