<?php


namespace App\Repositories;


use App\Models\GeoIpLocation;
use Illuminate\Database\Eloquent\Builder;

class GeoIpRepository
{
    public function findLocation(string $ip): ?GeoIpLocation
    {
        return GeoIpLocation::whereHas('geoIpBlocks', function (Builder $query) use ($ip) {
            $query->where('cidr', '>>=', $ip);
        })->first();
    }
}
