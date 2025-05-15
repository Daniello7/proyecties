<x-app-layout :title="__('Control Access')">
    <x-slot name="header">
        <h1 class="font-semibold text-3xl custom-gradient-text">
            {{ __('Key Control') }}
        </h1>
    </x-slot>
    <div class="py-8">
        <livewire:key-control.index/>
    </div>
</x-app-layout>
