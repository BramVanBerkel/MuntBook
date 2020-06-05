<?php

namespace App\Console\Commands\Gulden;

use App\Jobs\SyncBlock;
use App\Models\Block;
use App\Services\Gulden;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminated\Console\WithoutOverlapping;

class Check extends Command
{
    use WithoutOverlapping;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gulden:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if new blocks are available and syncs them to the database.';

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
     * @return mixed
     */
    public function handle()
    {
        //don't run if initial_sync is active
        if (config('gulden.initial_syncync') !== null) {
            return;
        }

        $guldenService = resolve(Gulden::class);
        $guldenBlockCount = $guldenService->getBlockCount();
        $dbBlockCount = Block::count();

        if ($dbBlockCount === 0) {
            // no blocks in db, set initial_sync to true to prevent double blocks
            config(['gulden.initial_sync' => $guldenBlockCount]);
        }

        foreach (range($dbBlockCount, $guldenBlockCount) as $height) {
            Log::info(sprintf("Blockcount: %d/%d", $height, $guldenBlockCount));

            dispatch(new SyncBlock($height));
        }
    }
}
