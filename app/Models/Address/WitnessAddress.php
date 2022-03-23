<?php


namespace App\Models\Address;


use App\Models\Block;
use App\Models\Vout;
use Illuminate\Support\Carbon;

/**
 * App\Models\Address\WitnessAddress
 *
 * @property int $id
 * @property string $address
 * @property \App\Enums\AddressTypeEnum $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read int $adjusted_weight
 * @property-read int $cooldown
 * @property-read bool $eligible_to_witness
 * @property-read bool $expired_from_inactivity
 * @property-read \Illuminate\Support\Carbon|null $first_seen
 * @property-read bool $in_cooldown
 * @property-read int $locked_from_block
 * @property-read Carbon $locked_from_block_timestamp
 * @property-read int $locked_until_block
 * @property-read Carbon $locked_until_block_timestamp
 * @property-read int $total_amount_locked
 * @property-read mixed $transactions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Vin[] $vins
 * @property-read int|null $vins_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Vout[] $vouts
 * @property-read int|null $vouts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WitnessAddressPart[] $witnessAddressParts
 * @property-read int|null $witness_address_parts_count
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddress whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddress whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WitnessAddress whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class WitnessAddress extends Address
{
    public function getTransactionsAttribute()
    {
        return $this->vouts()->where('type', '=', Vout::TYPE_WITNESS)->orderByDesc('created_at');
    }

    public function getFirstSeenAttribute(): ?Carbon
    {
        return $this->transactions->first()?->created_at;
    }

    public function getTotalAmountLockedAttribute(): int
    {
        return $this->witnessAddressParts()->sum('amount');
    }

    public function getLockedFromBlockAttribute(): int
    {
        return $this->witnessAddressParts()->first()->lock_from_block;
    }

    public function getLockedFromBlockTimestampAttribute(): Carbon
    {
        return Block::find($this->witnessAddressParts()->first()->lock_from_block)->created_at;
    }

    public function getLockedUntilBlockAttribute(): int
    {
        return $this->witnessAddressParts()->first()->lock_until_block;
    }

    public function getLockedUntilBlockTimestampAttribute(): Carbon
    {
        $seconds = (Block::max('height') - $this->witnessAddressParts()->first()->lock_until_block) * config('gulden.blocktime');

        return now()->subSeconds($seconds);
    }

    public function getAdjustedWeightAttribute(): int
    {
        return $this->witnessAddressParts()->first()->adjusted_weight;
    }

    public function getEligibleToWitnessAttribute(): bool
    {
        return $this->witnessAddressParts()->first()->eligible_to_witness;
    }

    public function getExpiredFromInactivityAttribute(): bool
    {
        return $this->witnessAddressParts()->first()->expired_from_inactivity;
    }

    /**
     * Check if address was active in the last 100 blocks
     * @return bool
     */
    public function getInCooldownAttribute(): bool
    {
        return $this->cooldown < 100;
    }

    /**
     * Get the amount of blocks that the address is in cooldown
     * @return int
     */
    public function getCooldownAttribute(): int
    {
        return Block::max('height') - $this->witnessAddressParts()->max('last_active_block');
    }
}
