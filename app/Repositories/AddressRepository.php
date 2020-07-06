<?php


namespace App\Repositories;



use App\Models\Address;
use Illuminate\Support\Collection;

class AddressRepository
{
    public static function create(Collection $data): Address
    {
        return Address::firstOrCreate(
            ['address' => $data->get('address')],
            ['type' => Address::TYPE_ADDRESS]
        );
    }
}
