<?php

namespace App\Services;

use App\Models\GeoIpLocation;
use App\Repositories\GeoIpRepository;

use Illuminate\Support\Collection;

class GeoIPService
{
    public function __construct(private GeoIpRepository $geoIpRepository) {}

    /**
     * Groups the $ips per country and returns the number of IPs per country
     */
    public function countCountries(Collection $ips): Collection
    {
        return $ips->map(function (string $ip): ?GeoIpLocation {
            return $this->geoIpRepository->findLocation($ip);
        })->groupBy('country_name')
            ->map(function (Collection $group): int {
                return $group->count();
            });
    }
}

