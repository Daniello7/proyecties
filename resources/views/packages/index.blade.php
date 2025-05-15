<x-app-layout>
    <x-slot name='header'>
        <h1 class='font-semibold text-3xl custom-gradient-text'>
            {{ __('Package') }}
        </h1>
    </x-slot>
    <livewire:packages.index/>
</x-app-layout>
