<?php

namespace App\Http\Controllers;

use App\Services\GeoIPService;
use App\Services\MuntService;
use Carbon\CarbonInterface;

class NodeInformationController extends Controller
{
    public function __invoke(MuntService $muntService, GeoIPService $geoIPService)
    {
        if (! $muntService->running()) {
            return view('pages.node-information', [
                'running' => false,
            ]);
        }

        $uptime = now()->subSeconds($muntService->getUptime())->toNow(CarbonInterface::DIFF_ABSOLUTE, parts: 3);
        $networkInfo = $muntService->getNetworkInfo();

        $peerInfo = $muntService->getPeerInfo();

        $ips = $peerInfo->pluck('addr')->map(function (string $addr) {
            //remove port from end of string
            $addr = substr($addr, 0, strrpos($addr, ':'));

            //remove brackets from ipv6 notation
            return str_replace(['[', ']'], '', $addr);
        });

        $countries = $geoIPService->countCountries($ips);

        $versions = $peerInfo
            ->groupBy('subver')
            ->mapWithKeys(fn ($group, $version) => [$version => $group->count()])
            ->sortDesc();

        return view('pages.node-information', [
            'running' => true,
            'networkInfo' => $networkInfo,
            'uptime' => $uptime,
            'countries' => $countries,
            'versions' => $versions,
        ]);
    }
}
