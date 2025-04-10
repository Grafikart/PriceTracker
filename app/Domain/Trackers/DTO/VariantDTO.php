<?php

namespace App\Domain\Trackers\DTO;

class VariantDTO
{
    public function __construct(
        public readonly string $image = '',
        public readonly string $name = '',
        public readonly string $url = '',
        public readonly string $id = '',
    ) {}
}
