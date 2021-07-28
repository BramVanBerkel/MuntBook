<?php

namespace App\Http\Controllers;

use App\Repositories\AverageBlocktimeRepository;
use Illuminate\Http\Request;

class AverageBlocktimeController extends Controller
{
    public function __construct(
        private AverageBlocktimeRepository $averageBlocktimeRepository
    ) {}

    public function index()
    {
        return view('layouts.pages.average-blocktime');
    }

    public function data()
    {
        $averageBlocktime = $this->averageBlocktimeRepository->getAverageBlocktime()->map(function($averageBlocktime) {
            return [
                'x' => $averageBlocktime->day,
                'y' => $averageBlocktime->seconds,
            ];
        });

        $blocksPerDay = $this->averageBlocktimeRepository->getBlocksPerDay()->map(function($blocksPerDay) {
            return [
                'x' => $blocksPerDay->day,
                'y' => $blocksPerDay->blocks,
            ];
        });

        return response()->json([
            'datasets' => [
                [
                    'label' => 'Average blocktime',
                    'yAxisID' => 'Average blocktime',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'fill' => false,
                    'data' => $averageBlocktime,
                ],
                [
                    'label' => 'Blocks per day',
                    'yAxisID' => 'Blocks per day',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'fill' => false,
                    'data' => $blocksPerDay,
                ],
            ],
        ]);
    }
}
