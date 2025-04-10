<?php

use App\Domain\Trackers\DecathlonTracker;
use Illuminate\Support\Facades\Http;

describe(DecathlonTracker::class, function () {

    $tracker = new DecathlonTracker(
        new \App\Infrastructure\Crawlers\CurlCrawler
    );

    beforeEach(function () {
        Http::preventStrayRequests();
        Http::fake([
            'decathlon.fr/p/*' => Http::response(file_get_contents(
                __DIR__.'/mocks/decathlon.html'
            )),
            'decathlon.fr/invalid' => Http::response(file_get_contents(
                __DIR__.'/mocks/404.html'
            )),
            'decathlon.fr/404' => Http::response(file_get_contents(
                __DIR__.'/mocks/404.html'
            ), 404),
        ]);
    });

    it('should get the correct ID', function () use ($tracker) {
        expect($tracker->id())->toBe('decathlon');
    });

    it('should check url', function (string $url, bool $expectation) use ($tracker) {
        expect($tracker->checkUrl($url))
            ->toBe($expectation, sprintf('Expected %s to be %s', $url, $expectation));
    })->with([
        ['https://www.decathlon.fr/p/velo-ville-single-speed-500-jaune/_/R-p-306292?mc=8612319&c=orange', true],
        ['https://www.decathlon.fr/tous-les-sports/roundnet', false],
    ]);

    it('should extract price from the page', function () use ($tracker) {
        expect($tracker->fetchPrice('https://www.decathlon.fr/p/velo-ville-single-speed-500-gris-carbone/_/R-p-306292?mc=8612319&c=orange'))->toBe(29999);
    });

    it('should extract product info from the page', function () use ($tracker) {
        $product = $tracker->fetchProduct('https://www.decathlon.fr/p/velo-ville-single-speed-500-gris-carbone/_/R-p-306292?mc=8612319&c=orange');
        expect($product->name)->toBe('VELO VILLE SINGLE SPEED 500 GRIS CARBONE')
            ->and($product->current_price)->toBe(29999)
            ->and($product->current_price)->toBe($product->lowest_price)
            ->and($product->current_price)->toBe($product->initial_price);
    });

    it('should throw a parse error for invalid HTML', function () use ($tracker) {
        $tracker->fetchProduct('https://www.decathlon.fr/invalid');
    })->throws(\App\Infrastructure\Crawlers\Exceptions\ParseException::class);

    it('should throw a server error for invalid response', function () use ($tracker) {
        $tracker->fetchProduct('https://www.decathlon.fr/404');
    })->throws(\App\Infrastructure\Crawlers\Exceptions\ServerErrorException::class);

});
