<x-app-layout :title="__('Control Access')">
    <x-slot name="header">
        <h2 class="font-semibold text-3xl custom-gradient-text">
            {{ __('New Person') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg dark:bg-gray-800 rounded-lg">
                <div class="text-gray-800 dark:text-gray-100 pb-8">
                    <form action="{{ route('person.store') }}" method="post">
                        @include('person.form-fields')
                        <x-primary-button type="submit" class="ml-12">{{ __('Save') }}</x-primary-button>
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
