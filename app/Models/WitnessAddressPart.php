<?php

namespace App\Models;

use App\Enums\WitnessAddressPartStatusEnum;
use App\Repositories\BlockRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WitnessAddressPart
 *
 * @property int $id
 * @property int $address_id
 * @property string $type
 * @property int $age
 * @property float $amount
 * @property int $raw_weight
 * @property int $adjusted_weight
 * @property int $adjusted_weight_final
 * @property int $expected_witness_period
 * @property int $estimated_witness_period
 * @property int $last_active_block
 * @property int $lock_from_block
 * @property int $lock_until_block
 * @property int $lock_period
 * @property bool $lock_period_expired
 * @property bool $eligible_to_witness
 * @property bool $expired_from_inactivity
 * @property int $fail_count
 * @property int $action_nonce
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cooldown
 * @property-read WitnessAddressPartStatusEnum $status
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart query()
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart whereActionNonce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart whereAdjustedWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart whereAdjustedWeightFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart whereEligibleToWitness($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart whereEstimatedWitnessPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart whereExpectedWitnessPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart whereExpiredFromInactivity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart whereFailCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart whereLastActiveBlock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart whereLockFromBlock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart whereLockPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart whereLockPeriodExpired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart whereLockUntilBlock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart whereRawWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddressPart whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

        if((app(BlockRepository::class)->currentHeight() - $this->last_active_block) < config('gulden.witness_cooldown_period')) {
            return WitnessAddressPartStatusEnum::COOLDOWN;
        }

        return WitnessAddressPartStatusEnum::ELIGIBLE_TO_WITNESS;
    }

    public function getCooldownAttribute()
    {
        return (app(BlockRepository::class)->currentHeight() - $this->last_active_block);
    }
}
