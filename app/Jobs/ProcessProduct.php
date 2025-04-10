<?php

namespace App\Jobs;

use App\Domain\Product\PriceLoweredEvent;
use App\Domain\Product\Product;
use App\Domain\Trackers\PriceTrackerFactory;
use App\Infrastructure\Crawlers\Exceptions\ServerErrorException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessProduct implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly Product $product
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(
        PriceTrackerFactory $factory
    ): void {
        $tracker = $factory->get($this->product->tracker_id);
        try {
            $price = $tracker->fetchPrice($this->product->url);
        } catch (ServerErrorException $e) {
            $this->product->status_code = $e->getCode();
            $this->product->save();
            throw $e;
        }
        if ($price < $this->product->current_price) {
            event(new PriceLoweredEvent($this->product, $price));
        }
        $this->product->setCurrentPrice($price);
        $this->product->save();
        $this->release(now()->addDay());
    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     *
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [30, 2 * 60, 60 * 60];
    }
}
