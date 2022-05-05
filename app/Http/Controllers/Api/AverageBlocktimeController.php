<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\BlockRepository;

class AverageBlocktimeController extends Controller
{
    public function __construct(private readonly BlockRepository $blockRepository)
    {
    }

    public function __invoke(): array
    {
        return [
            'averageBlockTimes' => $this->blockRepository->getAverageBlocktimes(),
            'blocksPerDay' => $this->blockRepository->getBlocksPerDay(),
        ];
    }
}
