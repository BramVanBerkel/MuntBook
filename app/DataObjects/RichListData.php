<?php

namespace App\DataObjects;

class RichListData
{
    public function __construct(
        public readonly int $index,
        public readonly string $address,
        public readonly float $value,
    ) {
    }
}
