<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl custom-gradient-text">
            {{ __('Edit Entry') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg dark:bg-gray-800 rounded-lg">
                <div class="text-gray-800 dark:text-gray-100 pb-8">
                    <form action="{{ route('person-entries.update', $personEntry) }}" method="post">
                        @include('person-entry.edit-form-fields')
                        <x-primary-button type="submit" class="ml-12">{{ __('Save') }}</x-primary-button>
                        @csrf
                        @method('PATCH')
                    </form>
                    @if ($errors->any())
                        <div class="bg-red-500 text-white p-2 rounded-lg mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
