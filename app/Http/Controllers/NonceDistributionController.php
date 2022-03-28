<?php

namespace App\Http\Controllers;

use App\Repositories\BlockRepository;

class NonceDistributionController extends Controller
{
    public function __invoke(BlockRepository $blockRepository)
    {
        return view('pages.nonce-distribution');
    }
}
