<?php

namespace App\Http\Controllers;

use App\Domain\Product\Product;
use App\Domain\Trackers\Exceptions\VariantsException;
use App\Http\Controllers\Trait\DatastarTrait;
use App\Http\Requests\PriceStoreRequest;
use App\Jobs\ProcessProduct;
use starfederation\datastar\enums\FragmentMergeMode;

class PricesController extends Controller
{
    use DatastarTrait;

    public function index()
    {
        return view('prices.index', [
            'products' => Product::all(),
        ]);
    }

    public function store(PriceStoreRequest $request)
    {
        $url = $request->validated('url');
        try {
            $product = $request->tracker->fetchProduct($url);
        } catch (VariantsException $e) {
            $this->renderFragment('prices.variants', [
                'variants' => $e->variants,
            ]);

            return;
        } catch (\Exception $e) {
            $this->renderFragment('components.flash-message', [
                'message' => $e->getMessage(),
                'type' => 'danger',
            ]);

            return;
        }
        $product->tracker_id = $request->tracker->id();
        $product->save();
        dispatch(
            new ProcessProduct($product)
        )->delay(now()->addDay());
        $this->renderFragment(
            'components.flash-message', [
                'message' => sprintf('Le produit a bien été ajouté'),
            ],
            '#flash'
        );
        $this->renderFragment(
            'prices.row', [
                'product' => $product,
            ],
            '#tbody',
            FragmentMergeMode::Prepend
        );

    }
}
