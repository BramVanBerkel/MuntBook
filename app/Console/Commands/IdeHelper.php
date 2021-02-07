<?php

namespace App\Console\Commands;

use Artisan;
use Illuminate\Console\Command;

class IdeHelper extends Command
{
    protected $signature = 'ide-helper';

    protected $description = 'Generates ide helper files';

    public function handle()
    {
        $this->info('Clearing compiled...');
        Artisan::call('clear-compiled');

        $this->info('Running ide-helper:generate...');
        Artisan::call('ide-helper:generate');

        $this->info('Running ide-helper:meta...');
        Artisan::call('ide-helper:meta');

        $this->info('Running ide-helper:models -N...');
        Artisan::call('ide-helper:models -N');
    }
}
