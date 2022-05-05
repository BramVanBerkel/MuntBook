<?php

namespace App\Services;

use App\Repositories\GeoIPRepository;
use Illuminate\Support\Collection;

class GeoIPService
{
    public function __construct(private readonly GeoIPRepository $geoIPRepository)
    {
    }

    public function countCountries(Collection $ips): Collection
    {
        return $ips->map(fn(string $ip) => [
            'country' => $this->geoIPRepository->findCity($ip),
            'ip' => $ip,
        ])->groupBy('country')
            ->map(fn(Collection $group): int => $group->count())->sortByDesc(fn($value, $key) => //make sure Unknown is always at the bottom, to not be confused with an actual country
($key === GeoIPRepository::UNKNOWN) ? -1 : $value);
    }
}
