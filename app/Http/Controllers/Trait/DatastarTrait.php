<?php

namespace App\Http\Controllers\Trait;

use starfederation\datastar\enums\FragmentMergeMode;
use starfederation\datastar\ServerSentEventGenerator;

trait DatastarTrait
{
    public function renderFragment(string $view, array $data, ?string $selector = null, FragmentMergeMode $mode = FragmentMergeMode::Append)
    {
        $sse = app(ServerSentEventGenerator::class);
        $options = $selector ?
            [
                'mergeMode' => $mode,
                'selector' => $selector,
            ] : [];
        $sse->mergeFragments(
            view($view, $data)->render(),
            $options
        );
    }
}
