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
        return match (Str::length($address)) {
            config('gulden.address_length') => Address::TYPE_ADDRESS,
            config('gulden.witness_length') => Address::TYPE_WITNESS_ADDRESS,
            default => null,
        };
    }
}
