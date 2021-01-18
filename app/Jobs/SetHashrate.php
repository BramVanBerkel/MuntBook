<?php

namespace App\Jobs;

use App\Models\Block;
use App\Services\GuldenService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetHashrate implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private int $height) {}

    public function handle(GuldenService $guldenService): void
    {
        $networkHashrate = $guldenService->getNetworkHashrate(height: $this->height);

        Block::find($this->height)->update([
            'hashrate' => $networkHashrate
        ]);
    }
}
