<?php

namespace App\Http\Controllers;

use App\Repositories\DifficultyRepository;
use App\Repositories\HashrateRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DifficultyHashrateController extends Controller
{
    public final const TIMEFRAMES = [30, 60, 180, 365];

    public final const AVERAGES = [1, 7, 30];

    //todo: fix
//    public function index()
//    {
//        return view('layouts.pages.difficulty-hashrate');
//    }

    public function data(Request $request, DifficultyRepository $difficultyRepository, HashrateRepository $hashrateRepository)
    {
        $request->validate([
            'timeframe' => [
                Rule::in(self::TIMEFRAMES),
            ],
            'average' => [
                Rule::in(self::AVERAGES),
            ],
        ]);

        $timeframe = $request->get('timeframe');
        $average = $request->get('average');

        $difficulty = cache()->remember("average-difficulty-{$timeframe}-{$average}",
            now()->addHour(),
            fn () => $difficultyRepository->getDifficulty($timeframe, $average)
                ->map(fn ($difficulty) => [
                    'x' => $difficulty->date,
                    'y' => $difficulty->average_difficulty,
                ]));

        $hashrate = cache()->remember("average-hashrate-{$timeframe}-{$average}",
            now()->addHour(),
            fn () => $hashrateRepository->getHashrate($timeframe, $average)
                ->map(fn ($hashRate) => [
                    'x' => $hashRate->date,
                    'y' => $hashRate->average_hashrate,
                ]));

        return response()->json([
            'datasets' => [
                [
                    'label' => 'Difficulty',
                    'yAxisID' => 'Difficulty',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'fill' => false,
                    'data' => $difficulty,
                ],
                [
                    'label' => 'Hashrate',
                    'yAxisID' => 'Hashrate',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'fill' => false,
                    'data' => $hashrate,
                ],
            ],
        ]);
    }
}
