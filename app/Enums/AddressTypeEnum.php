<?php

namespace App\Enums;

enum AddressTypeEnum: string
{
    case ADDRESS = 'ADDRESS';
    case WITNESS = 'WITNESS';
    case MINING = 'MINING';
}
