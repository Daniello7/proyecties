<x-app-layout>
    <x-slot name="header">
        <h1 class='font-semibold text-3xl custom-gradient-text'>
            {{ __('Human Resources') }}
        </h1>
    </x-slot>
    <div class="p-8">
        <section class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors">
            <x-header :content="__('Employees')"/>
            <livewire:internal-person-table/>
        </section>
    </div>
</x-app-layout>