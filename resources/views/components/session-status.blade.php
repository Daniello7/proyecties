@props(['status', 'flash'])

@if (isset($status) && session()->has($status))
    <div {{ $attributes->merge(['class' => 'custom-gradient-text font-bold']) }}>
        {{ session($status) }}
    </div>
@elseif(isset($flash) && session()->has($flash))
    <div {{ $attributes->merge(['class' => 'custom-gradient-text font-bold']) }}>
        {{ session($flash) }}
    </div>
@endif
