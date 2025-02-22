@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'custom-gradient-text font-bold', 'x-data' => '{ show:true }', 'x-init' => 'setTimeout(() => show = false, 5000)', 'x-show' => 'show']) }}>
        {{ $status }}
    </div>
@endif
