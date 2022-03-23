<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

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
