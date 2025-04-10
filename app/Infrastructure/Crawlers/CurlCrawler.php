<?php

namespace App\Infrastructure\Crawlers;

use App\Infrastructure\Crawlers\Exceptions\ParseException;
use App\Infrastructure\Crawlers\Exceptions\ServerErrorException;
use Dom\HTMLDocument;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class CurlCrawler
{
    public function getHTMLDocument(string $url): HTMLDocument
    {
        $response = $this->getResponse($url);
        if ($response->ok()) {
            return \Dom\HTMLDocument::createFromString((string) $response->getBody(), LIBXML_NOERROR);
        }

        throw new ServerErrorException(
            sprintf(__('Cannot reach %s, status code %s'), $url, $response->getStatusCode()),
            $response->getStatusCode()
        );
    }

    public function getNodeAttribute(HTMLDocument $dom, string $selector, string $attributeName): string
    {
        $node = $dom->querySelector($selector);
        if (! $node) {
            throw new ParseException(sprintf(__('Cannot find "%s" in the document'), $selector));
        }

        return $node->getAttribute('content') ?? '';
    }

    public function getNodeContent(HTMLDocument $dom, string $selector): string
    {
        $node = $dom->querySelector($selector);
        if (! $node) {
            throw new ParseException(sprintf(__('Cannot find "%s" in the document'), $selector));
        }

        return $node->textContent ?? '';
    }

    private function getResponse(string $url): Response
    {
        return Http::withHeaders([
            'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
            'accept-encoding' => 'gzip, deflate, br, zstd',
            'accept-language' => 'fr-FR,fr;q=0.9',
            'cache-control' => 'no-cache',
            'pragma' => 'no-cache',
            'priority' => 'u=0, i',
            'sec-ch-ua' => '"Brave";v="135", "Not-A.Brand";v="8", "Chromium";v="135"',
            'sec-ch-ua-mobile' => '?0',
            'sec-ch-ua-platform' => '"Linux"',
            'sec-fetch-dest' => 'document',
            'sec-fetch-mode' => 'navigate',
            'sec-fetch-site' => 'none',
            'sec-fetch-user' => '?1',
            'sec-gpc' => '1',
            'upgrade-insecure-requests' => '1',
            'user-agent' => 'Chrome-Lighthouse Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
        ]
        )->get($url);
    }
}
