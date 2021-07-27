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

        return response()->json([
            'datasets' => [
                [
                    'label' => 'Average blocktime',
                    'yAxisID' => 'Average blocktime',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'fill' => false,
                    'data' => $averageBlocktime,
                ],
            ]
        ]);
    }
}
