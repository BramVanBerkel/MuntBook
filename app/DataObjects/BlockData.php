<?php

namespace App\DataObjects;

use Carbon\Carbon;

class BlockData
{
    public function __construct(
        public int $height,
        public string $hash,
        public Carbon $timestamp,
        public float $value,
        public int $transactions,
        public int $version,
        public string $merkleRoot,
    ) {}
}
