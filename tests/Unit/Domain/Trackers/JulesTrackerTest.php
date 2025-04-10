<?php

use App\Domain\Trackers\DecathlonTracker;
use Illuminate\Support\Facades\Http;

describe(DecathlonTracker::class, function () {

    $tracker = new \App\Domain\Trackers\JulesTracker(
        new \App\Infrastructure\Crawlers\CurlCrawler
    );
    $productUrl = 'https://www.jules.com/fr-fr/p/100543613711.html';

    beforeEach(function () {
        Http::preventStrayRequests();
        Http::fake([
            'jules.com/fr-fr/p/*' => Http::response(file_get_contents(
                __DIR__.'/mocks/jules.html'
            )),
            'jules.com/invalid' => Http::response(file_get_contents(
                __DIR__.'/mocks/404.html'
            )),
            'jules.com/404' => Http::response(file_get_contents(
                __DIR__.'/mocks/404.html'
            ), 404),
        ]);
    });

    it('should get the correct ID', function () use ($tracker) {
        expect($tracker->id())->toBe('jules');
    });

    it('should check url', function (string $url, bool $expectation) use ($tracker) {
        expect($tracker->checkUrl($url))
            ->toBe($expectation, sprintf('Expected %s to be %s', $url, $expectation));
    })->with([
        [$productUrl, true],
        ['https://www.jules.com/fr-fr/l/jeans/', false],
    ]);

    it('should extract price from the page', function () use ($productUrl, $tracker) {
        expect($tracker->fetchPrice($productUrl))->toBe(2519);
    });

    it('should extract product info from the page', function () use ($productUrl, $tracker) {
        $product = $tracker->fetchProduct($productUrl);
        expect($product->name)->toBe('Sweat à capuche uni Orange foncé')
            ->and($product->current_price)->toBe(2519)
            ->and($product->current_price)->toBe($product->lowest_price)
            ->and($product->current_price)->toBe($product->initial_price);
    });

    it('should throw a parse error for invalid HTML', function () use ($tracker) {
        $tracker->fetchProduct('https://www.jules.com/invalid');
    })->throws(\App\Infrastructure\Crawlers\Exceptions\ParseException::class);

    it('should throw a server error for invalid response', function () use ($tracker) {
        $tracker->fetchProduct('https://www.jules.com/404');
    })->throws(\App\Infrastructure\Crawlers\Exceptions\ServerErrorException::class);

});
