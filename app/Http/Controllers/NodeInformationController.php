<?php

namespace App\Http\Controllers;

use App\Services\GuldenService;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class NodeInformationController extends Controller
{
    public function index(GuldenService $guldenService)
    {
        //format seconds to a human readable format
        $uptime = now()->subSeconds($guldenService->getUptime())->toNow(Carbon::DIFF_ABSOLUTE, parts: 3);

        return view('layouts.pages.node_information', [
            'networkInfo' => $guldenService->getNetworkInfo(),
            'uptime' => $uptime,
        ]);
    }
}
