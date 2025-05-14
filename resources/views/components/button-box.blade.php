<button {{ $attributes ?? '' }}>
    <div class="bg-white dark:bg-gray-800 py-12 px-8 gradient-border flex justify-center items-center rounded-3xl hover:font-bold">
        <p class="text-2xl text-blue-600 dark:text-pink-500 flex gap-2">{{ $slot }}</p>
    </div>
</button>
