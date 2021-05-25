<?php

namespace App\Http\Controllers;

use App\Services\GuldenService;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use GuzzleHttp\Exception\GuzzleException;

class NodeInformationController extends Controller
{
    public function index(GuldenService $guldenService)
    {
        if($running = $guldenService->running()) {
            $uptime = now()->subSeconds($guldenService->getUptime())->toNow(Carbon::DIFF_ABSOLUTE, parts: 3);
            $networkInfo = $guldenService->getNetworkInfo();
        }

        return view('layouts.pages.node_information', [
            'running' => $running,
            'networkInfo' => $networkInfo ?? null,
            'uptime' => $uptime ?? null,
        ]);
    }
}
