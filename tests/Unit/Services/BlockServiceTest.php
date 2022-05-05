<?php

namespace Tests\Unit\Services;

use App\Services\BlockService;
use Tests\TestCase;

class BlockServiceTest extends TestCase
{
    private BlockService $blockService;

    protected function setUp(): void
    {
        $this->blockService = app(BlockService::class);

        parent::setUp();
    }

    /** @test */
    public function block_subsidy_returns_the_correct_amount()
    {
        $this->assertSame(170_000_000, $this->blockService->getBlockSubsidy(1)->mining);
        $this->assertSame(1000, $this->blockService->getBlockSubsidy(250000)->mining);
        $this->assertSame(100, $this->blockService->getBlockSubsidy(250001)->mining);
        $this->assertSame(110, $this->blockService->getBlockSubsidy(1_030_001)->total());
        $this->assertSame(110, $this->blockService->getBlockSubsidy(config('gulden.pow2_phase_4_first_block_height'))->total());
        $this->assertSame(120, $this->blockService->getBlockSubsidy(config('gulden.pow2_phase_4_first_block_height') + 1)->total());
        $this->assertSame(120, $this->blockService->getBlockSubsidy(1_226_651)->total());
        $this->assertSame(200, $this->blockService->getBlockSubsidy(1_226_652)->total());
        $this->assertSame(90, $this->blockService->getBlockSubsidy(1_226_652)->mining);
        $this->assertSame(80, $this->blockService->getBlockSubsidy(1_226_652)->development);
        $this->assertSame(30, $this->blockService->getBlockSubsidy(1_226_652)->witness);
        $this->assertSame(200, $this->blockService->getBlockSubsidy(1_228_003)->total());
        $this->assertSame(160, $this->blockService->getBlockSubsidy(1_228_004)->total());
        $this->assertSame(50, $this->blockService->getBlockSubsidy(1_228_004)->mining);
        $this->assertSame(80, $this->blockService->getBlockSubsidy(1_228_004)->development);
        $this->assertSame(30, $this->blockService->getBlockSubsidy(1_228_004)->witness);
        $this->assertSame(50, $this->blockService->getBlockSubsidy(1_400_000)->mining);
        $this->assertSame(80, $this->blockService->getBlockSubsidy(1_400_000)->development);
        $this->assertSame(30, $this->blockService->getBlockSubsidy(1_400_000)->witness);
        $this->assertSame(10, $this->blockService->getBlockSubsidy(1_400_001)->mining);
        $this->assertSame(15, $this->blockService->getBlockSubsidy(1_400_001)->witness);
        $this->assertSame(65, $this->blockService->getBlockSubsidy(1_400_001)->development);
        $this->assertSame(10, $this->blockService->getBlockSubsidy(2_242_500)->mining);
        $this->assertSame(15, $this->blockService->getBlockSubsidy(2_242_500)->witness);
        $this->assertSame(65, $this->blockService->getBlockSubsidy(2_242_500)->development);
        $this->assertSame(5, $this->blockService->getBlockSubsidy(2_242_501)->mining);
        $this->assertSame(7.50, $this->blockService->getBlockSubsidy(2_242_501)->witness);
        $this->assertSame(32.5, $this->blockService->getBlockSubsidy(2_242_501)->development);
        $this->assertSame(0, $this->blockService->getBlockSubsidy(17_727_501)->total());
    }
}
