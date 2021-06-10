<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeoIpBlock extends Model
{
    protected $table = 'geoip_blocks';

    protected $fillable = [
        "cidr",
        "geoname_id",
    ];

    public function geoIpLocation(): BelongsTo
    {
        return $this->belongsTo(GeoIpLocation::class, 'geoname_id', 'geoname_id');
    }
}
