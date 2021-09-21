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

        return match ($type->label) {
            AddressTypeEnum::ADDRESS()->label => Address::firstWhere('address', '=', $address),
            AddressTypeEnum::MINING()->label => MiningAddress::firstWhere('address', '=', $address),
            AddressTypeEnum::WITNESS()->label => WitnessAddress::firstWhere('address', '=', $address),
            default => null,
        };
    }

    private function getType(string $address): AddressTypeEnum
    {
        dd($address);
        return Address::firstWhere('address', '=', $address)?->type;
    }
}
