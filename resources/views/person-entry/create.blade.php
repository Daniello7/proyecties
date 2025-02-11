<x-home-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('New Entry') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg dark:bg-gray-800">
                <div class="p-6 text-gray-800 dark:text-gray-100">
                    <form action="{{ route('person-entries.store') }}" method="post">
                        @include('person-entry.form-fields')
                        <x-primary-button type="submit">{{ __('Save') }}</x-primary-button>
                        @csrf
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
</x-home-layout>
