<?php

namespace App\Http\Controllers;

use App\Services\GeoIPService;
use App\Services\GuldenService;
use Carbon\Carbon;

class NodeInformationController extends Controller
{
    public function index(GuldenService $guldenService, GeoIPService $geoIPService)
    {
        if($running = $guldenService->running()) {
            $uptime = now()->subSeconds($guldenService->getUptime())->toNow(Carbon::DIFF_ABSOLUTE, parts: 3);
            $networkInfo = $guldenService->getNetworkInfo();
            $peerInfo = $guldenService->getPeerInfo();

            $ips = $peerInfo->pluck('addr')->map(function(string $addr) {
                //remove port from end of string
                $addr = substr($addr, 0, strrpos($addr, ":"));

                //remove brackets from ipv6 notation
                return str_replace(['[', ']'], '', $addr);
            });

            $countries = $geoIPService->countCountries($ips);
        }

        return view('layouts.pages.node_information', [
            'running' => $running,
            'networkInfo' => $networkInfo ?? null,
            'uptime' => $uptime ?? null,
            'countries' => $countries ?? null,
        ]);
    }
}
