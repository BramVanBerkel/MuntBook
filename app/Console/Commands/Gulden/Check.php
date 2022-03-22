<?php

namespace App\Console\Commands\Gulden;

use App\Jobs\ProcessBlock;
use App\Jobs\UpdateWitnessInfo;
use App\Models\Block;
use App\Services\BlockService;
use App\Services\GuldenService;
use App\Services\SyncService;
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
     * @param GuldenService $guldenService
     * @return void
     */
    public function handle(GuldenService $guldenService, SyncService $syncService)
    {
        $guldenHeight = $guldenService->getBlockCount();
        $dbHeight = Block::max('height') ?? 1;

        Log::info("Checking for new blocks. DB height: {$dbHeight}, Gulden height: {$guldenHeight}");

        if($dbHeight === $guldenHeight) {
            return;
        }

        $progress = $this->output->createProgressBar($guldenHeight - $dbHeight);

        Log::info(sprintf('Blockcount: %d/%d', $dbHeight, $guldenHeight));

        foreach(range($dbHeight, $guldenHeight) as $height) {
            $syncService->syncBlock($height);

            if($height >= config('gulden.first_phase_5_block')) {
                dispatch(new UpdateWitnessInfo());
            }

            $progress->advance();
        }

        $progress->finish();
    }
}
