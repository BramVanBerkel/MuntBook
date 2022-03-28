<?php

namespace App\DataObjects;

class NonceData
{
    public function __construct(
        public int $height,
        public int $preNonce,
        public int $postNonce,
    ) {
    }
}
