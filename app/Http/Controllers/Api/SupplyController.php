<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\BlockRepository;
use App\Services\BlockService;

class SupplyController extends Controller
{
    public function __construct(
        private BlockService $blockService,
        private BlockRepository $blockRepository,
    ) {
    }

    /**
     * Returns the total number of Guldens that will ever be mined.
     *
     * @return int
     */
    public function totalSupply()
    {
        return config('gulden.total_supply');
    }

    /**
     * Returns the total number of Guldens that have been mined up until now.
     *
     * To prevent long calculations, we start from a known block and supply.
     * At block 1.550.000 the supply was 561.870.520
     *
     * @param  BlockService  $blockService
     * @return int
     */
    public function circulatingSupply(BlockService $blockService)
    {
        $total = 561_870_520;

        foreach (range(1_550_000, $this->blockRepository->currentHeight()) as $height) {
            $total += $this->blockService->getBlockSubsidy($height)->total();
        }

        return (int) $total;
    }
}
