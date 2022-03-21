<?php

namespace App\Interfaces;

interface AddressRepositoryInterface
{
    public function getAddress(string $address);

    public function getTransactions(string $address);
}
