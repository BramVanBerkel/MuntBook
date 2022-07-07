<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\WitnessAddressPart;
use Illuminate\Database\Seeder;

class WitnessAddressPartsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $addresses = json_decode(file_get_contents('zip://database/seeders/addresses.json.zip#addresses.json'), true, 512, JSON_THROW_ON_ERROR);

        foreach ($addresses as $address => $parts) {
            $address = Address::where('address', '=', $address)->firstOrFail();

            $address->witnessAddressParts()->delete();

            foreach ($parts as $part) {
                $address->witnessAddressParts()
                    ->create([
                        'type' => $part['type'],
                        'age' => $part['age'],
                        'amount' => $part['amount'],
                        'raw_weight' => $part['raw_weight'],
                        'adjusted_weight' => $part['adjusted_weight'],
                        'adjusted_weight_final' => $part['adjusted_weight_final'],
                        'expected_witness_period' => $part['expected_witness_period'],
                        'estimated_witness_period' => $part['estimated_witness_period'],
                        'last_active_block' => $part['last_active_block'],
                        'lock_from_block' => $part['lock_from_block'],
                        'lock_until_block' => $part['lock_until_block'],
                        'lock_period' => $part['lock_period'],
                        'lock_period_expired' => $part['lock_period_expired'],
                        'eligible_to_witness' => $part['eligible_to_witness'],
                        'expired_from_inactivity' => $part['expired_from_inactivity'],
                        'fail_count' => $part['fail_count'],
                        'action_nonce' => $part['action_nonce'],
                    ]);
            }
        }
    }
}
