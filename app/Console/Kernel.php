<?php

namespace App\Console;

use App\Jobs\UpdateBittrexPrices;
use App\Jobs\UpdateDifficulty;
use App\Jobs\UpdateHashrate;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('horizon:snapshot')->everyFiveMinutes();

        $schedule->job(new UpdateHashrate())->everyMinute();

        $schedule->job(new UpdateDifficulty())->everyMinute();

        $schedule->job(new UpdateBittrexPrices())->everyMinute()->when(fn () => ! config('gulden.testnet'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
