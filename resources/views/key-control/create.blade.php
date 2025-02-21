<x-app-layout :title="__('Key Control')">
    <x-slot name='header'>
        <h1 class='font-semibold text-3xl custom-gradient-text'>
            {{ __('Key Control') }}
        </h1>
    </x-slot>
    <div class="py-8">
        <div class="flex flex-col gap-8 mx-auto sm:px-6 lg:px-8">
            <section class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors">
                <h2 class="text-xl font-semibold p-4 custom-gradient-text uppercase">
                    {{ __('Key Control').' - '.__('New Exit') }}
                </h2>
                <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
                <form action="{{ route('key-control.store') }}" method="post" class="p-8">
                    <livewire:key-control-create/>
                    <x-primary-button type="submit" class="mt-4">{{ __('Save') }}</x-primary-button>
                    @csrf
                </form>
            </section>
        </div>
    </div>
</x-app-layout>
