<?php

namespace App\Domain\Trackers;

use App\Domain\Product\Product;
use App\Domain\Trackers\DTO\VariantDTO;
use App\Domain\Trackers\Exceptions\VariantsException;
use App\Infrastructure\Crawlers\CurlCrawler;
use App\Infrastructure\Crawlers\Exceptions\ParseException;
use Illuminate\Support\Collection;

readonly class DecathlonTracker implements PriceTrackerInterface
{
    public function __construct(private CurlCrawler $crawler) {}

    public function id(): string
    {
        return 'decathlon';
    }

    public function checkUrl(string $link): bool
    {
        return (bool) preg_match('/^https:\/\/www.decathlon.fr\/p\/([^\/])+\/_\/R-p-[0-9]+\W/', $link);
    }

    /**
     * @throws VariantsException
     * @throws \App\Infrastructure\Crawlers\Exceptions\ParseException
     */
    public function fetchProduct(string $link): Product
    {
        $dom = $this->crawler->getHTMLDocument($link);
        $json = $this->crawler->getNodeContent($dom, 'script[type="application/ld+json"]');

        $data = json_decode($json, true);
        if (! $data) {
            throw new ParseException(
                __('Cannot parse SvelteKit data : '.json_last_error_msg())
            );
        }

        /**
         * @var Collection<int, array{
         *     "@type": string,
         *     "sku": string,
         *     "price": float,
         *     "priceCurrency": string,
         *     "availability": string,
         *     "itemCondition": string,
         *     "image": string,
         *     "seller": array,
         *     "url": string
         * }> $offers
         */
        $offers = collect($data['offers'])
            ->flatten(1)
            ->unique('url');

        if ($offers->count() === 1) {
            $offer = $offers->first();
        } else {
            $offer = $offers->where('url', $link)->first();
        }

        if (! $offer) {
            throw new VariantsException(
                $offers->map(fn (array $offer) => new VariantDTO(
                    image: $offer['image'],
                    name: explode('&c=', $offer['url'])[1],
                    url: $offer['url'],
                    id: $offer['sku']
                ))
            );
        }

        $price = intval(str_replace('.', '', $this->crawler->getNodeAttribute($dom, 'meta[property="product:original_price:amount"]', 'content')));

        $product = new Product;
        $product->name = $this->crawler->getNodeAttribute($dom, 'meta[property="og:title"]', 'content');
        $product->url = $link;
        $product->setCurrentPrice($price);

        return $product;
    }

    public function fetchPrice(string $link): int
    {
        return $this->fetchProduct($link)->current_price;
    }
}
