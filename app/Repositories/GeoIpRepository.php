<?php


namespace App\Repositories;


use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class GeoIPRepository
{
    public const UNKNOWN = 'Unknown';

    private ?Reader $reader;

    public function __construct()
    {
        try {
            $this->reader = new Reader(Storage::path('GeoLite2-Country.mmdb'));
        } catch (InvalidArgumentException $e) {
            Log::error($e);
            $this->reader = null;
        }
    }

    public function findCity(string $ip)
    {
        if($this->reader === null) {
            return "Unknown";
        }

        try {
            return $this->reader->country($ip)->country->name;
        } catch (AddressNotFoundException $e) {
            Log::error($e);
            return self::UNKNOWN;
        }
    }
}
