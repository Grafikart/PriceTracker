<?php

namespace App\Domain\Trackers;

use App\Domain\Product\Product;
use App\Infrastructure\Crawlers\CurlCrawler;

class JulesTracker implements PriceTrackerInterface
{
    public function __construct(private CurlCrawler $crawler) {}

    public function id(): string
    {
        return 'jules';
    }

    public function checkUrl(string $link): bool
    {
        return (bool) preg_match('/^https:\/\/www.jules.com\/fr-fr\/p\/[0-9]+.html/', $link);
    }

    public function fetchProduct(string $link): Product
    {
        $dom = $this->crawler->getHTMLDocument($link);
        $title = trim(explode('|', $this->crawler->getNodeContent($dom, 'title'))[0]);
        $price = intval(str_replace('.', '', $this->crawler->getNodeAttribute($dom, 'meta[property="product:price:amount"]', 'content')));
        $product = new Product;
        $product->name = $title;
        $product->url = $link;
        $product->setCurrentPrice($price);

        return $product;
    }

    public function fetchPrice(string $link): int
    {
        return $this->fetchProduct($link)->current_price;
    }
}
