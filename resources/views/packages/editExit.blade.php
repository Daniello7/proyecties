<x-app-layout>
    <x-slot name='header'>
        <h1 class='font-semibold text-3xl custom-gradient-text'>
            {{ __('Package').' - '.__('Edit Exit') }}
        </h1>
    </x-slot>
    <div class="py-8">
        <div class="flex flex-col gap-8 mx-auto sm:px-6 lg:px-8">
            <section class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors z-10">
                <x-header :content="__('Edit Exit')"/>
                <form action="{{ route('packages.update', ['id' => $package->id]) }}" method="post" class="px-8 pb-4">
                    @include('packages.create-form-fields', ['isExit' => true, 'isEditView' => true])
                    @csrf
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                    @method('PATCH')
                </form>
            </section>
        </div>
    </div>
</x-app-layout>
