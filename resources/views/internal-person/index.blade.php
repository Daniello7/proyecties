<x-app-layout :title="__('Internal Staff')">
    <x-slot name="header">
        <h1 class="font-semibold text-3xl custom-gradient-text">
            {{ __('Internal Staff') }}
        </h1>
    </x-slot>
    <div class="p-8">
        <section class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors">
            <h2 class="text-xl font-semibold p-4 custom-gradient-text uppercase">
                {{ __('Internal Staff') }}
            </h2>
            <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
            <livewire:internal-person-table class="m-4"/>
        </section>
    </div>
</x-app-layout>
