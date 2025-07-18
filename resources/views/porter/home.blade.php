<x-app-layout :title="__('Control Access')">
    <x-slot name="header">
        <h1 class="font-semibold text-3xl custom-gradient-text">
            {{ __('Control Access') }}
        </h1>
    </x-slot>
    <x-session-status status="status" class="text-lg mx-auto p-4"/>
    <div class="py-12 px-4 flex flex-col xl:flex-row gap-4">
        <div class="flex-[2] flex flex-col gap-8">
            <div class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg">
                <livewire:person-entries.home-table/>
            </div>
            <div class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg">
                <x-header :content="__('Package')"/>
                <livewire:packages.home-table/>
            </div>
        </div>
        <div class="flex-1 text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg max-h-max">
            <x-header :content="__('Keys')"/>
            <livewire:key-control.home-table/>
        </div>
    </div>
</x-app-layout>
