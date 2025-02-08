<x-home-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Person Entries') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-800 dark:text-gray-100">
                <x-person-entry-table info="last_entries"/>
            </div>
        </div>
    </div>
</x-home-layout>
