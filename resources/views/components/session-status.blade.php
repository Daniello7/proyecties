@props(['status', 'flash'])

@if (isset($status) && session()->has($status))
    <div {{ $attributes->merge(['class' => 'custom-gradient-text font-bold']) }} x-data='{ show:true }' x-init='setTimeout(() => show = false, 5000)' x-show='show'>
        {{ session($status) }}
    </div>
@elseif(isset($flash) && session()->has($flash))
    <div {{ $attributes->merge(['class' => 'custom-gradient-text font-bold']) }}>
        {{ session($flash) }}
    </div>
@endif
