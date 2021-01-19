<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Repositories\DifficultyRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DifficultyController extends Controller
{
    const TIMEFRAMES = [30, 60, 180, 365, 1095, -1];

    const AVERAGES = [1, 7, 30];

    public function index()
    {
        return view('layouts.pages.difficulty');
    }

    public function data(Request $request, DifficultyRepository $difficultyRepository): JsonResponse
    {
        $request->validate([
            'timeframe' => [
                Rule::in(self::TIMEFRAMES),
            ],
            'average' => [
                Rule::in(self::AVERAGES),
            ],
        ]);

        $data = $difficultyRepository->getDifficulty($request->get('timeframe', 180), $request->get('average', 7))
            ->map(function ($difficulty) {
                return [
                    'x' => $difficulty->date,
                    'y' => $difficulty->average_difficulty,
                ];
            });

        return response()->json([
            'datasets' => [
                [
                    'label' => 'Difficulty',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'data' => $data,
                ],
            ]
        ]);
    }
}
