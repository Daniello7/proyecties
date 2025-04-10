<x-app-layout :title="__('Control Access')">
    <x-slot name="header">
        <h1 class="font-semibold text-3xl custom-gradient-text">
            {{ __('Person - Details') }}
        </h1>
    </x-slot>
    <div class="py-8">
        <div class="mx-auto sm:px-6 lg:px-8 flex flex-col gap-8">
            <div class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors">
                <x-header :content="__('Information').' - '.$person->name.' '.$person->last_name"/>
                @include('person.person-details')
            </div>
            <div class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors">
                <x-header :content="__('Entry History')"/>
                <livewire:person-entries-show-table person_id="{{ $person->id }}" class="m-4 "/>
            </div>
        </div>
    </div>
</x-app-layout>