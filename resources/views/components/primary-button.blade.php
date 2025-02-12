<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gradient-to-br from-emerald-600 to-blue-600 hover:from-blue-600 hover:to-emerald-600 active:ring-2 ring-blue-600 dark:ring-violet-600 dark:from-pink-600 dark:to-violet-600 dark:hover:from-violet-600 dark:hover:to-pink-600 border-2 border-white dark:border-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition-all']) }}>
    {{ $slot }}
</button>
