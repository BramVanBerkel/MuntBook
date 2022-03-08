<?php

namespace App\Http\Controllers;

use App\DataObjects\NonceData;
use App\Models\Block;
use App\Repositories\BlockRepository;
use JetBrains\PhpStorm\NoReturn;

class NonceDistributionController extends Controller
{
    public function __invoke(BlockRepository $blockRepository)
    {


//        return response()->json([
//            'datasets' => [
//                [
//                    'label' => 'Pre-nonce distribution',
//                    'borderColor' => 'rgb(255, 99, 132)',
//                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
//                    'data' => $preNonceData,
//                ],
//                [
//                    'label' => 'Post-nonce distribution',
//                    'borderColor' => 'rgb(54, 162, 235)',
//                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
//                    'data' => $postNonceData,
//                ]
//            ]
//        ]);

        return view('pages.nonce-distribution');
    }
}
