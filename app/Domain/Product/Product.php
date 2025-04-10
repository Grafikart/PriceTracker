<?php

namespace App\Domain\Product;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperProduct
 */
class Product extends Model
{
    public $timestamps = true;

    /**
     * Set the current price for a product
     */
    public function setCurrentPrice(int $price): self
    {
        if (! $this->initial_price) {
            $this->initial_price = $price;
        }
        if (! $this->lowest_price || $price < $this->lowest_price) {
            $this->lowest_price = $price;
        }
        $this->current_price = $price;

        return $this;
    }
}
