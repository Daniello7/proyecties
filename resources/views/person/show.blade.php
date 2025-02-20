<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-3xl custom-gradient-text">
            {{ __('Person - Details') }}
        </h1>
    </x-slot>
    <div class="py-8">
        <div class="mx-auto sm:px-6 lg:px-8 flex flex-col gap-8">
            <div class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors">
                <h2 class="text-xl font-semibold p-4 custom-gradient-text uppercase">
                    {{ __('Information').' - '.$person->name.' '.$person->last_name }}
                </h2>
                <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
                @include('person.person-details')
            </div>
            <div class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors">
                <h2 class="text-xl font-semibold p-4 custom-gradient-text uppercase">
                    {{ __('Entry History') }}
                </h2>
                <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
                <livewire:person-entry-table info="show" person_id="{{ $person->id }}" class="m-4 "/>
            </div>
        </div>
    </div>
</x-app-layout>