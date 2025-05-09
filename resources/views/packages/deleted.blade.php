<x-app-layout>
    <x-slot name='header'>
        <h1 class='font-semibold text-3xl custom-gradient-text'>
            {{ __('Package').' - '.__('Recycle bin') }}
        </h1>
    </x-slot>
    <div class="py-8">
        <div class="flex flex-col gap-8 mx-auto sm:px-6 lg:px-8">
            <section class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors z-10">
                <x-header :content="__('Recycle bin')"/>
                <livewire:packages-deleted-table/>
            </section>
        </div>
    </div>
</x-app-layout>