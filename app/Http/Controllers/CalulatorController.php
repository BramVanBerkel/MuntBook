<?php

namespace App\Http\Controllers;

use App\Services\GuldenService;

class CalulatorController extends Controller
{
    public function __construct(
        private GuldenService $guldenService,
    ) {}

    public function witnessYieldCalculator()
    {
        $witnessInfo = $this->guldenService->getWitnessInfo();

        return view('pages.calculators.witness', [
            'networkWeight' => $witnessInfo->get('total_witness_weight_raw'),
            'networkWeightAdjusted' => $witnessInfo->get('total_witness_weight_eligible_adjusted'),
        ]);
    }
}
