<x-app-layout :title="__('My Documents') .' - PDF'">
    <x-slot name="header">
        <h1 class="font-semibold text-3xl custom-gradient-text">
            {{ __('My Documents') }} - PDF
        </h1>
    </x-slot>
    <x-session-status status="status" class="text-lg mx-auto p-4"/>
    <div class="py-12 px-4 flex flex-col xl:flex-row gap-4">
        <div class="flex-[2] flex flex-col gap-8">
            <div class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg">
                <livewire:pdf-export-index/>
            </div>
        </div>
    </div>
</x-app-layout>
