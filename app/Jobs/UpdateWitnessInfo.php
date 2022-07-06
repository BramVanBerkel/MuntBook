<?php

namespace App\Jobs;

use App\Enums\AddressTypeEnum;
use App\Models\Address;
use App\Repositories\Address\WitnessAddressRepository;
use App\Services\GuldenService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class UpdateWitnessInfo implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly int $height,
    ) {
    }

    public function handle(
        GuldenService $guldenService,
        WitnessAddressRepository $witnessAddressRepository
    ) {
        $witnessInfo = $guldenService->getWitnessInfo(verbose: true);

        $witnessInfo->get('witness_address_list')->groupBy('address')
            ->each(function (Collection $parts, string $address) use ($witnessAddressRepository) {
                $address = Address::query()
                    ->where('address', '=', $address)
                    ->where('type', '=', AddressTypeEnum::WITNESS)
                    ->firstOrFail();

                $witnessAddressRepository->syncParts($address, $parts);
            });
    }

    public function uniqueId()
    {
        return $this->height;
    }
}
