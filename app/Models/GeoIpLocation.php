<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GeoIpLocation extends Model
{
    protected $primaryKey = 'geoname_id';

    protected $table = 'geoip_locations';

    protected $fillable = [
        'geoname_id',
        'continent_name',
        'country_name',
    ];

    public function geoIpBlocks(): HasMany
    {
        return $this->hasMany(GeoIpBlock::class, 'geoname_id', 'geoname_id');
    }
}
