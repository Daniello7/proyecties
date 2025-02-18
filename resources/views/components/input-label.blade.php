@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-blue-700 dark:text-pink-500']) }}>
    {{ $value ?? $slot }}
</label>
