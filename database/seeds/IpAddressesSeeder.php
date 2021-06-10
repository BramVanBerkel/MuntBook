<?php

namespace Database\Seeders;

use App\Models\GeoIpBlock;
use App\Models\GeoIpLocation;
use App\Services\GeoIPUpdateService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IpAddressesSeeder extends Seeder
{
    public function __construct(private GeoIPUpdateService $geoIPUpdateService) {}

    public function run()
    {
        if(!$this->geoIPUpdateService->shouldUpdate()) {
            return;
        }

        $this->geoIPUpdateService->updateFiles();

        DB::beginTransaction();

        GeoIpLocation::truncate();

        GeoIpBlock::truncate();

        $this->geoIPUpdateService->importGeoLocations();

        $this->geoIPUpdateService->importIpBlocks();

        $this->geoIPUpdateService->removeFiles();

        DB::commit();
    }
}
