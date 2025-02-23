<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <section class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors z-10">
                    <!-- Header según el Role -->
                    @role('admin')
                    <x-header :content="__('Admin')"/>
                    @endrole
                    @role('porter')
                    <x-header :content="__('Control Access')"/>
                    @endrole
                    @role('rrhh')
                    <x-header :content="__('Human Resources')"/>
                    @endrole

                    <!-- Enlaces según Role -->
                    <div class="py-6 flex flex-row flex-wrap gap-4 justify-evenly *:z-0">
                        @role('admin')
                        <x-link-box :href="route('admin')">
                            {{ __('Admin') }}
                        </x-link-box>
                        @endrole
                        @role('porter')
                        @endrole
                        @hasanyrole('admin|porter')
                        <x-link-box :href="route('control-access')">
                            {{ __('Control Access') }}
                        </x-link-box>
                        <x-link-box :href="route('person-entries')">
                            {{ __('External Staff') }}
                        </x-link-box>
                        <x-link-box :href="route('control-access')">
                            {{ __('Package') }}
                        </x-link-box>
                        <x-link-box :href="route('control-access')">
                            {{ __('Key Control') }}
                        </x-link-box>
                        @endhasanyrole
                        @role('rrhh')
                        <x-link-box :href="route('internal-person')">
                            {{ __('Internal Staff') }}
                        </x-link-box>
                        <x-link-box :href="route('welcome')">
                            {{ __('HR') }}
                        </x-link-box>
                        @endrole
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
