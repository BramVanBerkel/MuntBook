<?php

namespace App\Services;

use App\Repositories\AverageBlocktimeRepository;
use Illuminate\Support\Collection;

class AverageBlocktimeService
{
    public function __construct(
        private AverageBlocktimeRepository $averageBlocktimeRepository
    ) {
    }

    public function getAverageBlocktime(): Collection
    {
        return $this->averageBlocktimeRepository->getAverageBlocktime()->map(function ($averageBlocktime) {
            return [
                'x' => $averageBlocktime->day,
                'y' => $averageBlocktime->seconds,
            ];
        });
    }

    public function getAverageBlocksPerDay(): Collection
    {
        return $this->averageBlocktimeRepository->getBlocksPerDay()->map(function ($blocksPerDay) {
            return [
                'x' => $blocksPerDay->day,
                'y' => $blocksPerDay->blocks,
            ];
        });
    }
}
