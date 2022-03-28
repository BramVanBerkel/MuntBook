<?php

namespace App\Services;

use App\Repositories\GeoIPRepository;
use Illuminate\Support\Collection;

class GeoIPService
{
    public function __construct(private GeoIPRepository $geoIPRepository)
    {
    }

    public function countCountries(Collection $ips): Collection
    {
        return $ips->map(function (string $ip) {
            return [
                'country' => $this->geoIPRepository->findCity($ip),
                'ip' => $ip,
            ];
        })->groupBy('country')
            ->map(function (Collection $group): int {
                return $group->count();
            })->sortByDesc(function ($value, $key) {
                //make sure Unknown is always at the bottom, to not be confused with an actual country
                return ($key === GeoIPRepository::UNKNOWN) ? -1 : $value;
            });
    }
}
