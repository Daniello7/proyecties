<x-app-layout>
    <x-slot name='header'>
        <h1 class='font-semibold text-3xl custom-gradient-text'>
            {{ __('Internal Staff').' - '.__('Details') }}
        </h1>
    </x-slot>
    <div class="py-8">
        <div class="mx-auto sm:px-6 lg:px-8 flex flex-col gap-8">
            <section class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors">
                <x-header :content="__('Information').' - '.$internalPerson->person->name.' '.$internalPerson->person->last_name"/>
                @include('internal-person.details')
                <livewire:person-document :person="$internalPerson->person"/>
            </section>
        </div>
    </div>
</x-app-layout>
