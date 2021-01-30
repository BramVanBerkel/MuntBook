<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WitnessAddressPart extends Model
{
    use HasFactory;

    protected $table = 'witness_address_parts';

    protected $fillable = [
        'address_id',
        'type',
        'age',
        'amount',
        'raw_weight',
        'adjusted_weight',
        'adjusted_weight_final',
        'expected_witness_period',
        'estimated_witness_period',
        'last_active_block',
        'lock_from_block',
        'lock_until_block',
        'lock_period',
        'lock_period_expired',
        'eligible_to_witness',
        'expired_from_inactivity',
        'fail_count',
        'action_nonce',
    ];

    public const TYPE_SCRIPT = 'SCRIPT';
    public const TYPE_POW2WITNESS = 'POW2WITNESS';

    public const TYPES = [
        self::TYPE_SCRIPT,
        self::TYPE_POW2WITNESS,
    ];
}
