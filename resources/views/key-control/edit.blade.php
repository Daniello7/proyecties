<x-app-layout :title="__('Control Access')">
    <x-slot name='header'>
        <h1 class='font-semibold text-3xl custom-gradient-text'>
            {{ __('Key Control') }}
        </h1>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg dark:bg-gray-800 rounded-lg">
                <div class="text-gray-800 dark:text-gray-100 pb-8">
                    <x-header :content="__('Key Control').' - '.__('Edit Exit')"/>
                    <form action="{{ route('key-control.update', $keyControl->id) }}" method="post" class="px-8">
                        @include('key-control.edit-form-fields')
                        <x-primary-button type="submit">{{ __('Save') }}</x-primary-button>
                        @csrf
                        @method('PATCH')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
