<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Repositories\BlockRepository;

class MissingBlockController extends Controller
{
    public function __construct(
        private BlockRepository $blockRepository,
    ) {}

    // todo: fix controller
    public function __invoke(int $block)
    {
        $difference = ($block - $this->blockRepository->currentHeight()) * config('gulden.blocktime');

        return view('pages.missing-block', [
            'block' => $block,
            'date' => now()->addSeconds($difference),
        ]);
    }
}
