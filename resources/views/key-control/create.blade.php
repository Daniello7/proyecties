<x-app-layout :title="__('Control Access')">
    <x-slot name='header'>
        <h1 class='font-semibold text-3xl custom-gradient-text'>
            {{ __('Key Control') }}
        </h1>
    </x-slot>
    <div class="py-8">
        <div class="flex flex-col gap-8 mx-auto sm:px-6 lg:px-8">
            <section class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors">
                <x-header :content="__('Key Control').' - '.__('New Exit')"/>
                <form action="{{ route('key-control.store') }}" method="post" class="p-8">
                    <livewire:key-select :is-form="true"/>
                    <x-primary-button type="submit" class="mt-4">{{ __('Save') }}</x-primary-button>
                    @csrf
                </form>
            </section>
        </div>
    </div>
</x-app-layout>
