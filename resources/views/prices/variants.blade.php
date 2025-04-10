<select
    id="url"
    class="border border-slate-700 rounded-sm px-2 min-w-40 flex items-center"
    name="url">
    @foreach($variants as $variant)
        <option class="p-2 hover:text-normal capitalize" value="{{ $variant->url }}">
            <img src="{{$variant->image}}" alt="">
            {{ $variant->name }}
        </option>
    @endforeach
</select>
