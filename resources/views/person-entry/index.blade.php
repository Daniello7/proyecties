<x-app-layout :title="__('Control Access')">
    <x-slot name="header">
        <h1 class="font-semibold text-3xl custom-gradient-text">
            {{ __('External Staff') }}
        </h1>
    </x-slot>
    <div class="py-8">
        <div class="flex flex-col gap-8 mx-auto sm:px-6 lg:px-8">
            <section class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors z-10">
                <x-header :content="__('Options')"/>
                <div class="py-6 flex flex-row gap-4 justify-evenly">
                    <x-link-box href="{{ route('person.create') }}">{{ __('New Person') }}</x-link-box>
                    <x-link-box href="{{ route('person.index') }}">{{ __('Person List') }}</x-link-box>
                </div>
            </section>
            <section class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors">
                <x-header :content="__('External Staff').' - '.__('New Entry')"/>
                <livewire:person-entries.index-table class="m-4"/>
            </section>
        </div>
    </div>
</x-app-layout>
