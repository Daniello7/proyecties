<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gradient-to-br from-red-600 to-orange-600 hover:from-orange-600 hover:to-red-600 active:ring-2 ring-red-600 border-2 border-white dark:border-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition-all']) }}>
    {{ $slot }}
</button>