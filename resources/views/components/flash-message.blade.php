@php
$type = $type ?? 'success';
@endphp
<div
    data-ref-el
    data-signals-visible="true"
    data-on-load__delay.3000ms="$visible = false"
    data-on-click="$visible = false"
    data-on-signal-change-visible__debounce.1000ms="$el.remove()"
    data-class="{'animate-fade-left': $visible, 'animate-fade-right animate-reverse': !$visible}"
    class="text-body py-3 px-2 rounded-md w-max max-w-[300px]  animate-duration-500 animate-fill-both "
    @class([
        'bg-green-700' => $type === 'success',
        'bg-red-700' => $type === 'danger'
    ])
>
    {{ $message }}
</div>
