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
        $this->assertSame(170000000, $this->blockService->getBlockSubsidy(1)->mining);
        $this->assertSame(1000, $this->blockService->getBlockSubsidy(250000)->mining);
        $this->assertSame(100, $this->blockService->getBlockSubsidy(250001)->mining);
        $this->assertSame(110, $this->blockService->getBlockSubsidy(1030001)->total());
        $this->assertSame(110, $this->blockService->getBlockSubsidy(config('gulden.pow2_phase_4_first_block_height'))->total());
        $this->assertSame(120, $this->blockService->getBlockSubsidy(config('gulden.pow2_phase_4_first_block_height') + 1)->total());
        $this->assertSame(120, $this->blockService->getBlockSubsidy(1226651)->total());
        $this->assertSame(200, $this->blockService->getBlockSubsidy(1226652)->total());
        $this->assertSame(90, $this->blockService->getBlockSubsidy(1226652)->mining);
        $this->assertSame(80, $this->blockService->getBlockSubsidy(1226652)->development);
        $this->assertSame(30, $this->blockService->getBlockSubsidy(1226652)->witness);
        $this->assertSame(200, $this->blockService->getBlockSubsidy(1228003)->total());
        $this->assertSame(160, $this->blockService->getBlockSubsidy(1228004)->total());
        $this->assertSame(50, $this->blockService->getBlockSubsidy(1228004)->mining);
        $this->assertSame(80, $this->blockService->getBlockSubsidy(1228004)->development);
        $this->assertSame(30, $this->blockService->getBlockSubsidy(1228004)->witness);
        $this->assertSame(50, $this->blockService->getBlockSubsidy(1400000)->mining);
        $this->assertSame(80, $this->blockService->getBlockSubsidy(1400000)->development);
        $this->assertSame(30, $this->blockService->getBlockSubsidy(1400000)->witness);
        $this->assertSame(10, $this->blockService->getBlockSubsidy(1400001)->mining);
        $this->assertSame(15, $this->blockService->getBlockSubsidy(1400001)->witness);
        $this->assertSame(65, $this->blockService->getBlockSubsidy(1400001)->development);
        $this->assertSame(10, $this->blockService->getBlockSubsidy(2242500)->mining);
        $this->assertSame(15, $this->blockService->getBlockSubsidy(2242500)->witness);
        $this->assertSame(65, $this->blockService->getBlockSubsidy(2242500)->development);
        $this->assertSame(5, $this->blockService->getBlockSubsidy(2242501)->mining);
        $this->assertSame(7.50, $this->blockService->getBlockSubsidy(2242501)->witness);
        $this->assertSame(32.5, $this->blockService->getBlockSubsidy(2242501)->development);
        $this->assertSame(0, $this->blockService->getBlockSubsidy(17727501)->total());
    }
}
