<?php

namespace App\Jobs;

use App\Models\Address\Address;
use App\Models\WitnessAddressPart;
use App\Services\GuldenService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateWitnessInfo implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(GuldenService $guldenService)
    {
        $witnessInfo = $guldenService->getWitnessInfo(verbose:  true);

        $addressList = $witnessInfo->get('witness_address_list')->groupBy('address');

        DB::beginTransaction();

        foreach ($addressList as $address => $parts) {
            $address = Address::firstWhere('address', '=', $address);

            if(!$address instanceof Address) {
                continue;
            }

            $address->witnessAddressParts()->delete();

            foreach ($parts as $part) {
                WitnessAddressPart::create([
                    'address_id' => $address->id,
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
                    'action_nonce' => $part->get('action_nonce'),
                ]);
            }
        }

        DB::commit();
    }
}
