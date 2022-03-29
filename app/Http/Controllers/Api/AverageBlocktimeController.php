<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\AverageBlocktimeRepository;
use App\Repositories\BlockRepository;
use App\Services\AverageBlocktimeService;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

class AverageBlocktimeController extends Controller
{
    public function __construct(private BlockRepository $blockRepository)
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
