<x-app-layout :title="__('Control Access')">
    <x-slot name="header">
        <h1 class="font-semibold text-3xl custom-gradient-text">
            {{ __('Key Control') }}
        </h1>
    </x-slot>
    <div class="py-8">
        <div class="flex flex-col gap-8 mx-auto sm:px-6 lg:px-8">
            <section class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors z-10">
                <h2 class="text-xl font-semibold p-4 custom-gradient-text uppercase">
                    {{ __('Options') }}
                </h2>
                <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
                <div class="py-6 flex flex-row gap-4 justify-evenly">
                    <a href="{{ route('key-control.create') }}">
                        <x-option-box>{{ __('New Exit') }} ↑</x-option-box>
                    </a> <a href="{{ route('keys.index') }}">
                        <x-option-box>{{ __('Search for Key') }}</x-option-box>
                    </a>
                </div>
            </section>
            <section class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors">
                <h2 class="text-xl font-semibold p-4 custom-gradient-text uppercase">
                    {{ __('Key Control').' - '.__('Latest Records') }}
                </h2>
                <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
                <livewire:key-control-table/>
            </section>
        </div>
    </div>
</x-app-layout>
