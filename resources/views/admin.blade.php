<x-app-layout>
    <x-slot name="header">
        <h1 class='font-semibold text-3xl custom-gradient-text'>
            Control 77 </h1>
    </x-slot>
    <div class="p-8">
        <div class="bg-white dark:bg-gray-800  rounded-lg shadow-md">
            <x-header :content="__('Admin')"/>
            <section class="py-6 flex flex-row gap-4 justify-evenly items-center">
                <x-link-box :href="route('key-control')" class="z-10">
                    <x-svg.key-icon class="w-8 h-8 stroke-blue-600"/>
                    {{ __('Keys Management') }}
                </x-link-box>
            </section>
        </div>
    </div>
</x-app-layout>