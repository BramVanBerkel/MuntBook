<?php

namespace App\Console\Commands\Gulden;

use App\Jobs\InsertBlock;
use App\Models\Block;
use App\Services\Gulden;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Check extends Command
{
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
        $guldenService = resolve(Gulden::class);

        $guldenBlockCount = $guldenService->getBlockCount();
        $dbBlockCount = Block::count();

        do {
            Log::info(sprintf("Blockcount: %d/%d", $dbBlockCount, $guldenBlockCount));

            dispatch(new InsertBlock($dbBlockCount));

            $dbBlockCount++;
        } while ($dbBlockCount !== $guldenBlockCount);
    }
}
