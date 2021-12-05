<?php


namespace App\Services;


use App\Enums\AddressTypeEnum;
use App\Models\Address\Address;
use Illuminate\Support\Str;

class AddressService
{
    public function getAddress(string $address): Address
    {
        return Address::firstOrCreate(
            ['address' => $address],
            ['type' => $this->getType($address)]
        );
    }

    private function getType(string $address): AddressTypeEnum
    {
        return match (Str::length($address)) {
            config('gulden.address_length') => AddressTypeEnum::ADDRESS,
            config('gulden.witness_address_length') => AddressTypeEnum::WITNESS,
            default => AddressTypeEnum::ADDRESS,
        };
    }
}
