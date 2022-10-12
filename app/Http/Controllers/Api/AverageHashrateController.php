<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\BlockRepository;

class AverageHashrateController extends Controller
{
    public function __construct(
        private readonly BlockRepository $blockRepository
    ) {
    }

    public function __invoke()
    {
        return $this->blockRepository->getAverageHashrate();
    }
}
