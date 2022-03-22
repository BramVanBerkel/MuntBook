<?php

namespace App\Http\Controllers;

use App\DataObjects\NonceData;
use App\Models\Block;
use App\Repositories\BlockRepository;
use JetBrains\PhpStorm\NoReturn;

class NonceDistributionController extends Controller
{
    public function __invoke(BlockRepository $blockRepository)
    {
        return view('pages.nonce-distribution');
    }
}
