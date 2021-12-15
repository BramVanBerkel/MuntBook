<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Repositories\BlockRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlockController extends Controller
{
    public function __construct(
        private BlockRepository $blockRepository,
        private TransactionRepository $transactionRepository,
    ) { }

    public function index(int $height): View
    {
        return view('pages.block')->with([
            'block' => $this->blockRepository->getBlock($height),
            'transactions' => $this->transactionRepository->getTransactions($height),
        ]);
    }
}
