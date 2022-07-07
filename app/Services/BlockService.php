<?php

namespace App\Services;

use App\DataObjects\BlockSubsidyData;
use App\Repositories\BlockRepository;
use Carbon\Carbon;

class BlockService
{
    public function __construct(
        private readonly BlockRepository $blockRepository
    ) {
    }

    public function getBlockSubsidy(int $height): BlockSubsidyData
    {
        if ($height === 1) {
            return new BlockSubsidyData(170_000_000, 0, 0); // First block (premine)
        } elseif ($height < config('gulden.fixed_reward_reduction_height')) {
            return new BlockSubsidyData(1000, 0, 0); // 1000 Gulden per block for first 250k blocks
        } elseif ($height < config('gulden.dev_block_subsidy_activation_height')) {
            return new BlockSubsidyData(100, 0, 0); // 100 Gulden per block (fixed reward/no halving)
        } elseif ($height < config('gulden.pow2_phase_4_first_block_height') + 1) {
            return new BlockSubsidyData(50, 20, 40); // 110 Gulden per block (fixed reward/no halving) - 50 mining, 40 development, 20 witness.
        } elseif ($height <= 1_226_651) {
            return new BlockSubsidyData(50, 30, 40); // 120 Gulden per block (fixed reward/no halving) - 50 mining, 40 development, 30 witness.
        } elseif ($height <= 1_228_003) {
            return new BlockSubsidyData(90, 30, 80); // 200 Gulden per block (fixed reward/no halving) - 90 mining, 80 development, 30 witness.
        } elseif ($height <= config('gulden.halving_introduction_height')) {
            return new BlockSubsidyData(50, 30, 80); // 160 Gulden per block (fixed reward/no halving) - 50 mining, 80 development, 30 witness.
        } elseif ($height < 1619997) {
            // 90 Gulden per block; 10 mining, 15 witness, 65 development
            return new BlockSubsidyData(10, 15, 65);
        } elseif ($height === 1619997) {
            // Once off large development fund distribution after which the per block development reward is dropped
            return new BlockSubsidyData(10, 15, 100_000_000);
        } else {
            // From this point on reward is as follows:
            // 90 Gulden per block; 10 mining, 15 witness, 65 development
            // Halving every 842500 blocks (roughly 4 years)
            // Rewards truncated to a maximum of 2 decimal places if large enough to have a number on the left of the decimal place
            // Otherwise truncated to 3 decimal places (if first place is occupied with a non zero number or otherwise a maximum of 4 decimal places
            // This is done to keep numbers a bit cleaner and more manageable
            // Halvings as follows:
            // 5 mining,          7.5 witness
            // 2 mining,          4 witness
            // 1 mining,          2 witness
            // 0.5 mining,        1 witness
            // 0.2 mining,        0.5 witness
            // 0.1 mining,        0.2 witness
            // 0.05 mining,       0.1 witness
            // 0.02 mining,       0.05 witness
            // 0.01 mining,       0.02 witness
            // 0.005 mining,      0.01 witness
            // 0.002 mining,      0.005 witness
            // 0.001 mining,      0.002 witness
            // 0.0005 mining,     0.001 witness
            // 0.0002 mining,     0.0005 witness
            // 0.0001 mining,     0.0002 witness
            // 0.00005 mining,    0.0001 witness
            // 0.00002 mining,    0.00005 witness
            // 0.00001 mining,    0.00002 witness
            // 0.000005 mining,   0.00001 witness
            // 0.000002 mining,   0.000005 witness
            // 0.000001 mining,   0.000002 witness
            // 0.0000005 mining,  0.000001 witness
            // 0.0000002 mining,  0.0000005 witness
            // 0.0000001 mining,  0.0000002 witness
            // 0.00000005 mining, 0.0000001 witness
            // 0.00000002 mining, 0.00000005 witness
            // 0.00000001 mining, 0.00000002 witness
            // NB! We could use some bit shifts and other tricks here to do the halving calculations (the specific truncation rounding we are using makes it a bit difficult)
            // However we opt instead for this simple human readable "table" layout so that it is easier for humans to inspect/verify this.
            $halvings = (int) round(($height - 1 - config('gulden.halving_introduction_height') - 167512) / 842500);

            return match ($halvings) {
                0 => new BlockSubsidyData(10, 15, 0),
                1 => new BlockSubsidyData(5, 7.5, 0),
                2 => new BlockSubsidyData(2, 4, 0),
                3 => new BlockSubsidyData(1, 2, 0),
                4 => new BlockSubsidyData(0.5, 1, 0),
                5 => new BlockSubsidyData(0.2, 0.5, 0),
                6 => new BlockSubsidyData(0.1, 0.2, 0),
                7 => new BlockSubsidyData(0.05, 0.1, 0),
                8 => new BlockSubsidyData(0.02, 0.05, 0),
                9 => new BlockSubsidyData(0.01, 0.02, 0),
                10 => new BlockSubsidyData(0.005, 0.01, 0),
                11 => new BlockSubsidyData(0.002, 0.005, 0),
                12 => new BlockSubsidyData(0.001, 0.002, 0),
                13 => new BlockSubsidyData(0.0005, 0.001, 0),
                14 => new BlockSubsidyData(0.0002, 0.0005, 0),
                15 => new BlockSubsidyData(0.0001, 0.0002, 0),
                16 => new BlockSubsidyData(0.00005, 0.0001, 0),
                17 => new BlockSubsidyData(0.00002, 0.00005, 0),
                18 => new BlockSubsidyData(0.00001, 0.00002, 0),
                19 => new BlockSubsidyData(0.000005, 0.00001, 0),
                20 => new BlockSubsidyData(0.000002, 0.000005, 0),
                21 => new BlockSubsidyData(0.000001, 0.000002, 0),
                22 => new BlockSubsidyData(0.0000005, 0.000001, 0),
                23 => new BlockSubsidyData(0.0000002, 0.0000005, 0),
                24 => new BlockSubsidyData(0.0000001, 0.0000002, 0),
                25 => new BlockSubsidyData(0.00000005, 0.0000001, 0),
                26 => new BlockSubsidyData(0.00000002, 0.00000005, 0),
                27 => new BlockSubsidyData(0.00000001, 0.00000002, 0),
                default => ($height <= config('gulden.final_subsidy_block')) ?
                    new BlockSubsidyData(0.0001, 0.0002, 0.0012) :
                    new BlockSubsidyData(0, 0, 0)
            };
        }
    }

    /**
     * Calculates the approximate date the block will be mined.
     */
    public function calculateMinedAtDate(int $height): Carbon
    {
        $seconds = ($height - $this->blockRepository->currentHeight()) * config('gulden.blocktime');

        return now()->addSeconds($seconds);
    }
}
