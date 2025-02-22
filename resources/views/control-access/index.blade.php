<x-app-layout :title="__('Control Access')">
    <x-slot name="header">
        <h1 class="font-semibold text-3xl custom-gradient-text">
            {{ __('Control Access') }}
        </h1>
    </x-slot>
    <x-session-status :status="session('status')" class="text-lg mx-auto p-4"/>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg">
                <div class="flex flex-row justify-between">
                    <h2 class="text-xl font-bold p-4 custom-gradient-text uppercase">
                        {{ __('External Staff') }}
                    </h2>
                </div>
                <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
                <livewire:person-entry-table/>
            </div>
        </div>
    </div>
</x-app-layout>
