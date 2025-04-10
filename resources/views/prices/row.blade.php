<tr class="text-sm">
    <td class="p-3 ps-0">
        <a href="{{ $product->url }}" class="underline hover:text-normal" target="_blank">
            {{ $product->name }}
        </a>
    </td>
    <td class="p-3 ps-0 text-end">
        {{ Number::currency($product->initial_price / 100, in: 'EUR') }}
    </td>
    <td class="p-3 ps-0 text-end">
        <div
            @class([
                "inline-flex items-center font-medium whitespace-nowrap text-xs py-1 rounded-md px-2 text-body",
                "bg-gray-400/40" => $product->current_price === $product->initial_price,
                "bg-green-400/40" => $product->current_price < $product->initial_price,
                "bg-red-400/40" => $product->current_price > $product->initial_price
            ])>
            {{ Number::currency($product->current_price / 100, in: 'EUR') }}
        </div>
    </td>
    <td class="p-3 ps-0 text-end">
        {{ Number::currency($product->lowest_price / 100, in: 'EUR') }}
    </td>
    <td class="p-3 ps-0 text-end">
        {{ $product->updated_at->diffForHumans() }}
    </td>
    <td class="p-3 ps-0 text-end">
    </td>
</tr>
