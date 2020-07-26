<?php


namespace App\Repositories;

use App\Models\Address;
use Illuminate\Support\Str;

class AddressRepository
{
    public static function create(string $address): Address
    {
        return Address::firstOrCreate(
            ['address' => $address],
            ['type' => self::getType($address)]
        );
    }

    private static function getType(string $address): ?string
    {
        switch (Str::length($address)) {
            case config('gulden.address_length'):
                return Address::TYPE_ADDRESS;
            case config('gulden.witness_length');
                return Address::TYPE_WITNESS_ADDRESS;
            default:
                return null;
        }
    }
}
