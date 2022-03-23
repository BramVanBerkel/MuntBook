<?php

namespace App\Http\Controllers;

use App\Repositories\AverageBlocktimeRepository;
use App\Services\AverageBlocktimeService;
use Illuminate\Http\Request;

class AverageBlocktimeController extends Controller
{
    public function __construct(
        private AverageBlocktimeService $averageBlocktimeService
    ) {}

    // todo: fix
//    public function index()
//    {
//        return view('layouts.pages.average-blocktime');
//    }

    public function data()
    {
        $averageBlocktime = $this->averageBlocktimeService->getAverageBlocktime();

        $blocksPerDay = $this->averageBlocktimeService->getAverageBlocksPerDay();

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
