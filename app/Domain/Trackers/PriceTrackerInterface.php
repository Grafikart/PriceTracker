<?php

namespace App\Domain\Trackers;

use App\Domain\Product\Product;
use App\Domain\Trackers\Exceptions\VariantsException;

interface PriceTrackerInterface
{
    // Unique ID to identify this tracker
    public function id(): string;

    // Check if a link is can be handled by this tracker
    public function checkUrl(string $link): bool;

    /**
     * Resolve the product from a URL
     *
     * @throws VariantsException
     * @throws \App\Infrastructure\Crawlers\Exceptions\ParseException
     */
    public function fetchProduct(string $link): Product;

    /**
     * Resolve the price (in cents) for a product
     *
     * @throws \App\Infrastructure\Crawlers\Exceptions\ParseException
     * @throws \App\Infrastructure\Crawlers\Exceptions\ServerErrorException
     */
    public function fetchPrice(string $link): int;
}
