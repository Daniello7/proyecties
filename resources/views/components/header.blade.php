@props(['content' => ''])
<div class="flex  flex-row flex-nowrap justify-between">
    <h2 class="text-xl font-semibold p-4 custom-gradient-text uppercase">
        {{ $content }}
    </h2>
    {{ $slot }}
</div>
<hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
