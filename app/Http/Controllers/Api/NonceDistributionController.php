<?php

namespace App\Http\Controllers\Api;

use App\DataObjects\NonceData;
use App\Http\Controllers\Controller;
use App\Repositories\BlockRepository;

class NonceDistributionController extends Controller
{
    public function __construct(
        private readonly BlockRepository $blockRepository,
    ) {
    }

    public function __invoke()
    {
        $nonces = $this->blockRepository->getLatestNonces();

        $preNonceData = $nonces->map(fn(NonceData $nonce) => [
            'x' => $nonce->height,
            'y' => $nonce->preNonce,
        ]);

        $postNonceData = $nonces->map(fn(NonceData $nonce) => [
            'x' => $nonce->height,
            'y' => $nonce->postNonce,
        ]);

        return response()->json([
            'preNonceData' => $preNonceData,
            'postNonceData' => $postNonceData,
        ]);
    }
}
