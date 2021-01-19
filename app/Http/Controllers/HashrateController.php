<?php

namespace App\Http\Controllers;

use App\Repositories\HashrateRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HashrateController extends Controller
{
    public function index()
    {
        return view('layouts.pages.hashrate');
    }

    public function data(Request $request, HashrateRepository $hashrateRepository): JsonResponse
    {
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
