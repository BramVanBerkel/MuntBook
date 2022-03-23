<?php

if (!function_exists('gmp_hexdec')) {
    /**
     * Converts a hex value to an arbitrarily long number
     *
     * @param string $n
     * @return GMP
     */
    function gmp_hexdec(string $n): GMP
    {
        $gmp = gmp_init(0);
        $mult = gmp_init(1);
        for ($i = strlen($n) - 1; $i >= 0; $i--, $mult = gmp_mul($mult, 16)) {
            $gmp = gmp_add($gmp, gmp_mul($mult, hexdec($n[$i])));
        }
        return $gmp;
    }
}
