<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Repositories\BlockRepository;
use Illuminate\View\View;

class BlockController extends Controller
{
    public function __construct(
        private readonly BlockRepository $blockRepository,
    ) {
    }

    public function __invoke(Block $block): View
    {
        return view('pages.block')->with([
            'block' => $this->blockRepository->getBlock($block->height),
            'transactions' => $this->blockRepository->getTransactions($block->height),
        ]);
    }
}
