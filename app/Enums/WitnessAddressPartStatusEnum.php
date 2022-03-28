<?php

namespace App\Enums;

enum WitnessAddressPartStatusEnum: string
{
    case LOCK_PERIOD_EXPIRED = 'lock_period_expired';
    case EXPIRED_FROM_INACTIVITY = 'expired_from_inactivity';
    case NOT_ELIGIBLE_TO_WITNESS = 'not_eligible_to_witness';
    case COOLDOWN = 'cooldown';
    case ELIGIBLE_TO_WITNESS = 'eligible_to_witness';

    public function label(): string
    {
        return match ($this) {
            self::LOCK_PERIOD_EXPIRED => 'Lock period expired',
            self::EXPIRED_FROM_INACTIVITY => 'Expired from inactivity',
            self::NOT_ELIGIBLE_TO_WITNESS => 'Not eligible to witness',
            self::COOLDOWN => 'In cooldown',
            self::ELIGIBLE_TO_WITNESS => 'Eligible to witness',
        };
    }

    public function type(): string
    {
        return match ($this) {
            self::LOCK_PERIOD_EXPIRED, self::EXPIRED_FROM_INACTIVITY, self::NOT_ELIGIBLE_TO_WITNESS => 'danger',
            self::COOLDOWN => 'warning',
            self::ELIGIBLE_TO_WITNESS => 'success',
        };
    }
}
