<?php


namespace App\Models\Address;



use App\Models\Block;
use App\Models\Vout;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Carbon;

class WitnessAddress extends Address
{
    public function transactions(): Paginator
    {
        return $this->vouts()->where('type', '=', Vout::TYPE_WITNESS)->orderByDesc('created_at')->paginate();
    }

    public function getFirstSeenAttribute(): Carbon
    {
        return now();
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

    public function getCooldownAttribute(): int
    {
        return Block::max('height') - $this->witnessAddressParts()->max('last_active_block');
    }
}
