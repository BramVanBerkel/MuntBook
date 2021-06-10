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

if (!function_exists('parse_csv')) {
    /**
     * Parses the given csv $file
     * If $header is passed, the first line of the csv is treated as the header
     * This could probably be optimized by using streams, more efficient data handling, etc.
     * @note Not fit for large files, because it reads the whole csv into memory
     */
    function parse_csv(string $file, bool $header = true): array
    {
        $contents = Storage::get($file);
        $lines = preg_split('/\r\n|\r|\n/', $contents);

        $headers = [];

        if ($header) {
            $headers = str_getcsv($lines[0]);
            unset($lines[0]);
        }

        $result = array_map(function ($line) use ($headers) {
            $data = str_getcsv($line);

            if (!empty($headers)) {
                if (count($data) !== count($headers)) {
                    return null;
                }

                return array_combine($headers, $data);
            }

            return $data;
        }, $lines);

        //filter data
        $result = array_filter($result, (!empty($headers)) ? 'is_array' : 'is_string');

        //reset array keys
        return array_values($result);
    }
}
