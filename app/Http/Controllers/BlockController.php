<?php

namespace App\Http\Controllers;

use App\Repositories\BlockRepository;
use Illuminate\View\View;

class BlockController extends Controller
{
    public function __construct(
        private BlockRepository $blockRepository,
    ) {
    }

    public function __invoke(int $height): View
    {
        return view('pages.block')->with([
            'block' => $this->blockRepository->getBlock($height),
            'transactions' => $this->blockRepository->getTransactions($height),
        ]);
    }
}
