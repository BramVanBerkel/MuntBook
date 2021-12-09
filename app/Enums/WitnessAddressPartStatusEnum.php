<?php

namespace App\Enums;

use App\Models\WitnessAddressPart;

enum WitnessAddressPartStatusEnum
{
    case LOCK_PERIOD_EXPIRED;
    case EXPIRED_FROM_INACTIVITY;
    case NOT_ELIGIBLE_TO_WITNESS;
    case COOLDOWN;
    case ELIGIBLE_TO_WITNESS;

    public function label(): string
    {
        return match($this) {
            self::LOCK_PERIOD_EXPIRED => 'Lock period expired',
            self::EXPIRED_FROM_INACTIVITY => 'Expired from inactivity',
            self::NOT_ELIGIBLE_TO_WITNESS => 'Not eligible to witness',
            self::COOLDOWN => 'In cooldown',
            self::ELIGIBLE_TO_WITNESS => 'Eligible to witness',
        };
    }

    public function type(): string
    {
        return match ($this){
            self::LOCK_PERIOD_EXPIRED, self::EXPIRED_FROM_INACTIVITY, self::NOT_ELIGIBLE_TO_WITNESS => 'danger',
            self::COOLDOWN => 'warning',
            self::ELIGIBLE_TO_WITNESS => 'success',
        };
    }
}
