<x-app-layout :title="__('My Documents') .' - PDF'">
    <x-slot name="header">
        <h1 class="font-semibold text-3xl custom-gradient-text">
            {{ __('My Documents') }}
        </h1>
    </x-slot>
    <x-session-status status="status" class="text-lg mx-auto p-4"/>
    <livewire:document-export-list/>
</x-app-layout>
