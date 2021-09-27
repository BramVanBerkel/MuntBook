<?php


namespace App\Repositories;


use App\Enums\AddressTypeEnum;
use App\Models\Address\Address;
use App\Models\Address\MiningAddress;
use App\Models\Address\WitnessAddress;

class AddressRepository
{
    public function findAddress(string $address): ?Address
    {
        $type = $this->getType($address);

        return match ($type) {
            AddressTypeEnum::ADDRESS() => Address::firstWhere('address', '=', $address),
            AddressTypeEnum::MINING() => MiningAddress::firstWhere('address', '=', $address),
            AddressTypeEnum::WITNESS() => WitnessAddress::firstWhere('address', '=', $address),
            default => null,
        };
    }

    private function getType(string $address): AddressTypeEnum
    {
        return Address::where('address', '=', $address)->firstOrFail()->type;
    }
}
