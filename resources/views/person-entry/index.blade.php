<x-home-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-3xl text-gray-800 dark:text-gray-200">
            {{ __('External Person') }}
        </h1>
    </x-slot>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="text-gray-800 dark:text-gray-100 border bg-white rounded-lg p-2 shadow-lg">
                <h2 class="text-xl font-semibold px-4 ">
                    {{ __('New Entry') }}
                </h2>
                <form action="" method="GET" class="text-center py-2">
                    <label for="search">{{ __('Filter') }}:</label>
                    <x-text-input id="search" name="search" class="p-1"/>
                    <x-primary-button type="submit">{{ __('Search') }}</x-primary-button>
                </form>
                <x-person-entry-table info="last_entries" class="m-4"/>
            </div>
        </div>
    </div>
</x-home-layout>
