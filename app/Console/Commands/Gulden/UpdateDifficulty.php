<?php

namespace App\Console\Commands\Gulden;

use App\Services\GuldenService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class UpdateDifficulty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gulden:update-difficulty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets current difficulty and stores it in the cache';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(GuldenService $guldenService)
    {
        $difficulty = $guldenService->getDifficulty();

        Cache::put('difficulty', round($difficulty));
    }
}
