<?php

namespace App\Http\Controllers;

use App\Repositories\HashrateRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class HashrateController extends Controller
{
    const TIMEFRAMES = [30, 60, 180, 365, 1095, -1];

    const AVERAGES = [1, 7, 30];

    public function index()
    {
        return view('layouts.pages.hashrate');
    }

    public function data(Request $request, HashrateRepository $hashrateRepository): JsonResponse
    {
        $request->validate([
            'timeframe' => [
                Rule::in(self::TIMEFRAMES),
            ],
            'average' => [
                Rule::in(self::AVERAGES),
            ],
        ]);

        $hashrate = $hashrateRepository->getHashrate($request->get('timeframe'), $request->get('average'))
            ->map(function($hashRate) {
                return [
                    'x' => $hashRate->date,
                    'y' => $hashRate->average_hashrate,
                ];
            });

        return response()->json([
            'datasets' => [
                [
                    'label' => 'Hashrate',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'data' => $hashrate,
                ],
            ]
        ]);
    }
}
