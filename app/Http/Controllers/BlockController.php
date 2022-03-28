<?php

namespace App\Http\Controllers;

use App\Repositories\BlockRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\View\View;

class BlockController extends Controller
{
    public function __construct(
        private BlockRepository $blockRepository,
    ) {
    }

    public function __invoke(int $height): View
    {
        try {
            $block = $this->blockRepository->getBlock($height);
        } catch (ModelNotFoundException) {
            abort(404);
        }

        return view('pages.block')->with([
            'block' => $block,
            'transactions' => $this->blockRepository->getTransactions($height),
        ]);
    }
}
