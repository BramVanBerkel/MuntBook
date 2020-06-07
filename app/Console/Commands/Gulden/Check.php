<?php

namespace App\Console\Commands\Gulden;

use App\Jobs\SyncBlock;
use App\Models\Block;
use App\Services\Gulden;
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
     * @param Gulden $guldenService
     * @return void
     * @throws InvalidArgumentException
     */
    public function handle(Gulden $guldenService)
    {
        $guldenBlockCount = $guldenService->getBlockCount();
        $dbBlockCount = Block::max('height');

        Log::info("Checking for new blocks. DB height: {$dbBlockCount}, Gulden height: {$guldenBlockCount}");

        if($dbBlockCount === $guldenBlockCount) {
            return;
        }

        foreach(range($dbBlockCount, $guldenBlockCount) as $height) {
            if(!Cache::has("syncblock-{$height}")) {
                Log::info(sprintf("Blockcount: %d/%d", $height, $guldenBlockCount));

                Cache::set("syncblock-{$height}", true);

                dispatch(new SyncBlock($height));
            }
        }
    }
}
