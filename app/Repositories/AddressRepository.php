<?php


namespace App\Repositories;

use App\Models\Address;

class AddressRepository
{
    public static function create(string $address): Address
    {
        return Address::firstOrCreate(
            ['address' => $address],
            ['type' => Address::TYPE_ADDRESS]
        );
    }
}
