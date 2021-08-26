<?php


namespace App\Repositories;


use App\Models\Address\Address;
use Illuminate\Support\Collection;

class WitnessAddressRepository
{
    public static function syncParts(Collection $parts)
    {
        $address = Address::firstWhere('address', '=', $parts->first()->get('address'));

        $parts->each(function(Collection $part) use($address) {
            $address->witnessAddressParts()->updateOrCreate([
                'action_nonce' => $part->get('action_nonce'),
            ], [
                'type' => $part->get('type'),
                'age' => $part->get('age'),
                'amount' => $part->get('amount'),
                'raw_weight' => $part->get('raw_weight'),
                'adjusted_weight' => $part->get('adjusted_weight'),
                'adjusted_weight_final' => $part->get('adjusted_weight_final'),
                'expected_witness_period' => $part->get('expected_witness_period'),
                'estimated_witness_period' => $part->get('estimated_witness_period'),
                'last_active_block' => $part->get('last_active_block'),
                'lock_from_block' => $part->get('lock_from_block'),
                'lock_until_block' => $part->get('lock_until_block'),
                'lock_period' => $part->get('lock_period'),
                'lock_period_expired' => $part->get('lock_period_expired'),
                'eligible_to_witness' => $part->get('eligible_to_witness'),
                'expired_from_inactivity' => $part->get('expired_from_inactivity'),
                'fail_count' => $part->get('fail_count'),
            ]);
        });
    }
}
