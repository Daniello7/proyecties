<x-home-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-3xl text-gray-800 dark:text-gray-200">
            {{ __('Control Access') }}
        </h1>
    </x-slot>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="text-gray-800 dark:text-gray-100 border bg-white rounded-lg p-2 shadow-lg">
                <x-person-entry-table/>
            </div>
        </div>
    </div>
</x-home-layout>
