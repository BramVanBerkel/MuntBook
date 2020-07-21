<?php

namespace App\Console\Commands\Gulden;

use App\Jobs\SyncBlock;
use App\Models\Block;
use App\Services\GuldenService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminated\Console\WithoutOverlapping;
use Psr\SimpleCache\InvalidArgumentException;

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
     * @param GuldenService $guldenService
     * @return void
     * @throws InvalidArgumentException
     */
    public function handle(GuldenService $guldenService)
    {
        $guldenHeight = $guldenService->getBlockCount();
        $dbHeight = Block::max('height') ?? 1;

        Log::info("Checking for new blocks. DB height: {$dbHeight}, Gulden height: {$guldenHeight}");

        if($dbHeight === $guldenHeight) {
            return;
        }

        foreach(range($dbHeight, $guldenHeight) as $height) {
            if(!Cache::has("syncblock-{$height}") && !Block::whereKey($height)->exists()) {
                Log::info(sprintf("Blockcount: %d/%d", $height, $guldenHeight));

//                if(config('queue.default') !== 'sync'){
//                    Cache::set("syncblock-{$height}", true);
//                }

                dispatch((new SyncBlock($height))->onConnection('sync'));
            }
        }
    }
}
