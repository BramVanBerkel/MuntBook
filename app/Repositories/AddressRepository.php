<?php


namespace App\Repositories;

use App\Enums\AddressTypeEnum;
use App\Models\Address;
use Illuminate\Support\Str;

class AddressRepository
{
    public function create(string $address): Address
    {
        return Address::firstOrCreate(
            ['address' => $address],
            ['type' => $this->getType($address)]
        );
    }

    private function getType(string $address): ?string
    {
        return match (Str::length($address)) {
            config('gulden.address_length') => AddressTypeEnum::ADDRESS(),
            config('gulden.witness_address_length') => AddressTypeEnum::WITNESS(),
            default => null,
        };
    }
}
