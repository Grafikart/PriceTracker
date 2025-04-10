<?php

namespace App\Domain\Trackers\Exceptions;

use App\Domain\Trackers\DTO\VariantDTO;
use Illuminate\Support\Collection;

/**
 * The product was found but there are variations
 */
class VariantsException extends \Exception
{
    public function __construct(
        /**
         * @var Collection<int, VariantDTO>
         */
        public readonly Collection $variants
    ) {

        parent::__construct(__('A variant must be choosen for this product'));
    }
}
