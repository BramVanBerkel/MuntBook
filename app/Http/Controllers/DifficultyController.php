<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Repositories\DifficultyRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DifficultyController extends Controller
{
    public function index()
    {
        return view('layouts.pages.difficulty');
    }

    public function data(Request $request, DifficultyRepository $difficultyRepository): JsonResponse
    {
        $data = $difficultyRepository->getDifficulty($request->get('timeframe'), $request->get('average'))
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
