<?php

namespace App\Models;

use App\Enums\WitnessAddressPartStatusEnum;
use App\Repositories\BlockRepository;
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

    public function getStatusAttribute(): WitnessAddressPartStatusEnum
    {
        if($this->lock_period_expired) {
            return WitnessAddressPartStatusEnum::LOCK_PERIOD_EXPIRED;
        }

        if($this->eligible_to_witness) {
            return WitnessAddressPartStatusEnum::NOT_ELIGIBLE_TO_WITNESS;
        }

        if($this->expired_from_inactivity) {
            return WitnessAddressPartStatusEnum::EXPIRED_FROM_INACTIVITY;
        }

        if((app(BlockRepository::class)->getCurrentHeight() - $this->last_active_block) < 100) {
            return WitnessAddressPartStatusEnum::COOLDOWN;
        }

        return WitnessAddressPartStatusEnum::ELIGIBLE_TO_WITNESS;
    }

    public function getCooldownAttribute()
    {
        return (app(BlockRepository::class)->getCurrentHeight() - $this->last_active_block);
    }
}
