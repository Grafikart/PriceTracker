@extends('layout')

@section('main')

    <div class="fixed top-4 right-4" id="flash">
    </div>

    <div class="flex justify-between">
        <h1 class="text-2xl font-bold text-slate-100">{{ __('Tracked prices') }}</h1>
        <form
            action="{{ route('prices.store') }}"
            data-on-submit="@post('{{ route('prices.store') }}', {contentType: 'form'})"
            data-indicator-fetching
            method="post"
            class="flex justify-end gap-2">
            @csrf
            <input
                id="url"
                type="text"
                class="border border-slate-700 rounded-sm px-2"
                placeholder="{{ __('Product URL') }}"
                name="url">
            <button
                type="submit"
                class="bg-indigo-600 px-4 rounded-sm py-2 text-slate-300 hover:bg-indigo-700 disabled:bg-gray-600"
                data-attr-disabled="$fetching"
            >
                {{ __('Add') }}
            </button>
        </form>
        @error('url')
            <p>
                {{ $message }}
            </p>
        @enderror
    </div>

    <table class="[:where(&)]:min-w-full table-fixed divide-y divide-slate-800 whitespace-nowrap mt-6">
        <thead>
        <tr class="text-slate-200 font-medium">
            @foreach([__('Product'), __('Base price'), __('Current price'),  __('Lowest price'), __('Updated at'), __('Actions')] as $column)
                <th class="py-3 px-3 first:ps-0 last:pe-0 text-start text-sm font-medium **:data-flux-table-sortable:last:me-0">{{ $column }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody class="divide-y divide-slate-800" id="tbody">
        @foreach($products as $product)
            @include('prices.row', ['product' => $product])
        @endforeach
        </tbody>
    </table>


@endsection
