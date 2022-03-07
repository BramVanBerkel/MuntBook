<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Repositories\BlockRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlockController extends Controller
{
    public function __construct(
        private BlockRepository $blockRepository,
    ) { }

    public function index(int $height): View
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
