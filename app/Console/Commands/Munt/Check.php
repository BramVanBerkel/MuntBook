<?php

namespace App\Console\Commands\Munt;

use App\Jobs\UpdateWitnessInfo;
use App\Models\Block;
use App\Services\MuntService;
use App\Services\SyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminated\Console\WithoutOverlapping;

class Check extends Command
{
    use WithoutOverlapping;

    protected $signature = 'munt:check';

    protected $description = 'Checks if new blocks are available and syncs them to the database.';

    public function handle(MuntService $muntService, SyncService $syncService)
    {
        $blockchainHeight = $muntService->getBlockCount();
        $localHeight = Block::max('height') ?? 1;

        Log::info("Checking for new blocks. local height: {$localHeight}, blockchain height: {$blockchainHeight}");

        if ($localHeight === $blockchainHeight) {
            return;
        }

        $progress = $this->output->createProgressBar($blockchainHeight - $localHeight);

        Log::info(sprintf('Blockcount: %d/%d', $localHeight, $blockchainHeight));

        foreach (range($localHeight, $blockchainHeight) as $height) {
            $syncService->syncBlock($height);

            if ($height >= config('munt.first_phase_5_block')) {
                dispatch(new UpdateWitnessInfo($height));
            }

            $progress->advance();
        }

        $progress->finish();
    }
}
