<?php

namespace App\Domain\Trackers;

use Illuminate\Support\Collection;

class PriceTrackerFactory
{
    /**
     * Retrieve the list of trackers instantiated
     *
     * @return Collection<int, PriceTrackerInterface>
     */
    public function all(): Collection
    {
        return collect(config('prices.trackers'))
            ->map(fn (string $className) => app($className));
    }

    /**
     * Retrieve the first tracker corresponding to the ID
     */
    public function get(string $id): PriceTrackerInterface
    {
        return collect(config('prices.trackers'))
            ->map(fn (string $className) => app($className))
            ->where(fn (PriceTrackerInterface $tracker) => $tracker->id() === $id)
            ->firstOrFail();
    }
}
