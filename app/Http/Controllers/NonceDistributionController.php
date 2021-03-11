<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Routing\Controller;

class NonceDistributionController extends Controller
{
    public function index()
    {
        return view('layouts.pages.nonce_distribution');
    }

    public function data()
    {
        $blocks = Block::select(['height', 'pre_nonce', 'post_nonce'])->orderByDesc('height')->limit(1000)->get();

        $preNonceData = $blocks->map(function ($block) {
            return [
                'x' => $block->height,
                'y' => $block->pre_nonce,
            ];
        });

        $postNonceData = $blocks->map(function ($block) {
            return [
                'x' => $block->height,
                'y' => $block->post_nonce,
            ];
        });

        return response()->json([
            'datasets' => [
                [
                    'label' => 'Pre-nonce distribution',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'data' => $preNonceData,
                ],
                [
                    'label' => 'Post-nonce distribution',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'data' => $postNonceData,
                ]
            ]
        ]);
    }
}
