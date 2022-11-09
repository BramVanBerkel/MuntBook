<?php

namespace App\Console\Commands\Munt;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class Exec extends Command
{
    protected $signature = 'munt:exec {instruction*}';

    protected $description = 'Executes the given instruction via Munt-cli and returns the output';

    public function handle()
    {
        $instruction = implode(' ', $this->argument('instruction'));

        $result = shell_exec(sprintf('./binaries/Munt-cli --datadir=binaries/datadir %s', $instruction));

        if (Str::isJson($result) && !is_numeric($result)) {
            dd(json_decode($result));
        }

        echo($result);
    }
}
