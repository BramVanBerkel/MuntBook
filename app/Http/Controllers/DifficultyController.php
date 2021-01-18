<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DifficultyController extends Controller
{
    public function index()
    {
        return view('layouts.pages.difficulty');
    }

    public function data()
    {
        $difficulty = DB::table('blocks')->select([
            DB::raw("date_trunc('day', created_at) AS day"),
            DB::raw("avg(difficulty) as difficulty"),
        ])->groupBy('day')->whereBetween('created_at', [now()->subDays(100), now()])->get()->map(function ($diff) {
            return [
                'x' => $diff->day,
                'y' => $diff->difficulty,
            ];
        });

        return response()->json([
            'datasets' => [
                [
                    'label' => 'Difficulty',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'data' => $difficulty,
                ],
            ]
        ]);
    }
}
