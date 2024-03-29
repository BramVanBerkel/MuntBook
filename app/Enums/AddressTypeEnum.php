<?php

namespace App\Enums;

use App\Interfaces\AddressRepositoryInterface;
use App\Repositories\Address\AddressRepository;
use App\Repositories\Address\MiningAddressRepository;
use App\Repositories\Address\WitnessAddressRepository;
use Illuminate\Contracts\View\View;

enum AddressTypeEnum: string
{
    case ADDRESS = 'ADDRESS';
    case WITNESS = 'WITNESS';
    case MINING = 'MINING';

    public function getRepository(): AddressRepositoryInterface
    {
        return match ($this) {
            self::MINING => app(MiningAddressRepository::class),
            self::WITNESS => app(WitnessAddressRepository::class),
            default => app(AddressRepository::class),
        };
    }

    public function getView(): View
    {
        return match ($this) {
            self::WITNESS => view('pages.witness-address'),
            self::MINING => view('pages.mining-address'),
            default => view('pages.address'),
        };
    }
}
