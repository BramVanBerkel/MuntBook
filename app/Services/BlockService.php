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
            // First block (premine)
            return new BlockSubsidyData(
                mining: 170_000_000,
                witness: 0,
                development: 0
            );
        } elseif ($height < config('munt.fixed_reward_reduction_height')) {
            // 1000 Munt per block for first 250k blocks
            return new BlockSubsidyData(
                mining: 1000,
                witness: 0,
                development: 0
            );
        } elseif ($height < config('munt.dev_block_subsidy_activation_height')) {
            // 100 Munt per block (fixed reward/no halving)
            return new BlockSubsidyData(
                mining: 100,
                witness: 0,
                development: 0
            );
        } elseif ($height < config('munt.pow2_phase_4_first_block_height') + 1) {
            // 110 Munt per block (fixed reward/no halving) - 50 mining, 40 development, 20 witness.
            return new BlockSubsidyData(
                mining: 50,
                witness: 20,
                development: 40
            );
        } elseif ($height <= 1_226_651) {
            // 120 Munt per block (fixed reward/no halving) - 50 mining, 40 development, 30 witness.
            return new BlockSubsidyData(
                mining: 50,
                witness: 30,
                development: 40
            );
        } elseif ($height <= 1_228_003) {
            // 200 Munt per block (fixed reward/no halving) - 90 mining, 80 development, 30 witness.
            return new BlockSubsidyData(
                mining: 90,
                witness: 30,
                development: 80
            );
        } elseif ($height <= config('munt.halving_introduction_height')) {
            // 160 Munt per block (fixed reward/no halving) - 50 mining, 80 development, 30 witness.
            return new BlockSubsidyData(
                mining: 50,
                witness: 30,
                development: 80
            );
        } elseif ($height < 1619997) {
            // 90 Munt per block; 10 mining, 15 witness, 65 development
            return new BlockSubsidyData(
                mining: 10,
                witness: 15,
                development: 65
            );
        } elseif ($height === 1619997) {
            // Once off large development fund distribution after which the per block development reward is dropped
            return new BlockSubsidyData(
                mining: 10,
                witness: 15,
                development: 100_000_000
            );
        } else {
            // From this point on reward is as follows:
            // 90 Munt per block; 10 mining, 15 witness, 65 development
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
            $halvings = (int) round(($height - 1 - config('munt.halving_introduction_height') - 167512) / 842500);

            return match ($halvings) {
                0 => new BlockSubsidyData(mining: 10, witness: 15, development: 0),
                1 => new BlockSubsidyData(mining: 5, witness: 7.5, development: 0),
                2 => new BlockSubsidyData(mining: 2, witness: 4, development: 0),
                3 => new BlockSubsidyData(mining: 1, witness: 2, development: 0),
                4 => new BlockSubsidyData(mining: 0.5, witness: 1, development: 0),
                5 => new BlockSubsidyData(mining: 0.2, witness: 0.5, development: 0),
                6 => new BlockSubsidyData(mining: 0.1, witness: 0.2, development: 0),
                7 => new BlockSubsidyData(mining: 0.05, witness: 0.1, development: 0),
                8 => new BlockSubsidyData(mining: 0.02, witness: 0.05, development: 0),
                9 => new BlockSubsidyData(mining: 0.01, witness: 0.02, development: 0),
                10 => new BlockSubsidyData(mining: 0.005, witness: 0.01, development: 0),
                11 => new BlockSubsidyData(mining: 0.002, witness: 0.005, development: 0),
                12 => new BlockSubsidyData(mining: 0.001, witness: 0.002, development: 0),
                13 => new BlockSubsidyData(mining: 0.0005, witness: 0.001, development: 0),
                14 => new BlockSubsidyData(mining: 0.0002, witness: 0.0005, development: 0),
                15 => new BlockSubsidyData(mining: 0.0001, witness: 0.0002, development: 0),
                16 => new BlockSubsidyData(mining: 0.00005, witness: 0.0001, development: 0),
                17 => new BlockSubsidyData(mining: 0.00002, witness: 0.00005, development: 0),
                18 => new BlockSubsidyData(mining: 0.00001, witness: 0.00002, development: 0),
                19 => new BlockSubsidyData(mining: 0.000005, witness: 0.00001, development: 0),
                20 => new BlockSubsidyData(mining: 0.000002, witness: 0.000005, development: 0),
                21 => new BlockSubsidyData(mining: 0.000001, witness: 0.000002, development: 0),
                22 => new BlockSubsidyData(mining: 0.0000005, witness: 0.000001, development: 0),
                23 => new BlockSubsidyData(mining: 0.0000002, witness: 0.0000005, development: 0),
                24 => new BlockSubsidyData(mining: 0.0000001, witness: 0.0000002, development: 0),
                25 => new BlockSubsidyData(mining: 0.00000005, witness: 0.0000001, development: 0),
                26 => new BlockSubsidyData(mining: 0.00000002, witness: 0.00000005, development: 0),
                27 => new BlockSubsidyData(mining: 0.00000001, witness: 0.00000002, development: 0),
                default => ($height <= config('munt.final_subsidy_block')) ?
                    new BlockSubsidyData(mining: 0.0001, witness: 0.0002, development: 0.0012) :
                    new BlockSubsidyData(mining: 0, witness: 0, development: 0)
            };
        }
    }

    /**
     * Calculates the approximate date the block will be mined.
     */
    public function calculateMinedAtDate(int $height): Carbon
    {
        $seconds = ($height - $this->blockRepository->currentHeight()) * config('munt.blocktime');

        return now()->addSeconds($seconds);
    }
}
