<?php

namespace App\Repositories;

use Exception;
use GeoIp2\Database\Reader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GeoIPRepository
{
    public const UNKNOWN = 'Unknown';

    private ?Reader $reader;

    public function __construct()
    {
        try {
            $this->reader = new Reader(Storage::path('GeoLite2-Country.mmdb'));
        } catch (Exception $e) {
            Log::error($e);
            $this->reader = null;
        }
    }

    public function findCity(string $ip)
    {
        if ($this->reader === null) {
            return self::UNKNOWN;
        }

        try {
            return $this->reader->country($ip)->country->name;
        } catch (Exception $e) {
            Log::error($e);

            return self::UNKNOWN;
        }
    }
}
