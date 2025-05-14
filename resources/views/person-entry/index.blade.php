<x-app-layout :title="__('Control Access')">
    <x-slot name="header">
        <h1 class="font-semibold text-3xl custom-gradient-text">
            {{ __('External Staff') }}
        </h1>
    </x-slot>
    <livewire:person-entries.index/>
</x-app-layout>
