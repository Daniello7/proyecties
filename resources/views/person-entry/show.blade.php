<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-3xl custom-gradient-text">
            {{ __('External Staff') }}
        </h1>
    </x-slot>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors">
                <h2 class="text-xl font-semibold p-4 custom-gradient-text uppercase">
                    {{ __('New Entry') }}
                </h2>
                <hr class="mx-2 border-blue-600 dark:border-pink-600 text- opacity-50">
                <form action="" method="GET" class="text-center py-2">
                    <label for="search">{{ __('Filter') }}:</label>
                    <x-text-input id="search" name="search" class="p-1"/>
                    <x-primary-button type="submit">{{ __('Search') }}</x-primary-button>
                </form>
                <livewire:person-entry-table info="last_entries" class="m-4"/>
            </div>
        </div>
    </div>
</x-app-layout>