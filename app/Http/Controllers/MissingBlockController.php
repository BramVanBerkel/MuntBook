<?php

namespace App\Http\Controllers;

use App\Repositories\BlockRepository;
use App\Services\BlockService;
use Illuminate\View\View;

class MissingBlockController extends Controller
{
    public function __construct(
        private readonly BlockRepository $blockRepository,
        private readonly BlockService $blockService,
    ) {
    }

    public function __invoke(int $block): View
    {
        $date = $this->blockService->calculateMinedAtDate($block);

        return view('pages.missing-block', [
            'block' => $block,
            'date' => $date,
        ]);
    }
}
