<?php

namespace App\Http\Controllers;

use App\Repositories\BlockRepository;

class MissingBlockController extends Controller
{
    public function __construct(
        private BlockRepository $blockRepository,
    ) {}

    public function __invoke(int $block)
    {
        $difference = ($block - $this->blockRepository->currentHeight()) * config('gulden.blocktime');

        return view('pages.missing-block', [
            'block' => $block,
            'date' => now()->addSeconds($difference),
        ]);
    }
}
