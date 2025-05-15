<div class="py-8">
    <div class="flex flex-col gap-8 mx-auto sm:px-6 lg:px-8">
        <section class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors z-10">
            <x-header :content="__('Options')"/>
            <div class="py-6 flex flex-row gap-4 justify-evenly">
                <x-button-box wire:click="openPackageList">
                    {{ __('Latest Records') }}
                    <x-svg.package-icon class="w-7 h-7 stroke-blue-600"/>
                </x-button-box>
                <x-button-box wire:click="openCreateReception">
                    {{ __('New Reception') }}
                    <x-svg.package-reception-icon/>
                </x-button-box>
                <x-button-box wire:click="openCreateShipping">
                    {{ __('New Shipping') }}
                    <x-svg.package-shipping-icon/>
                </x-button-box>
                <x-button-box wire:click="openPackagesDeleted">
                    {{ __('Recycle bin') }}
                    <x-svg.recycle-bin-icon class="w-7 h-7 stroke-blue-600 dark:stroke-pink-500"/>
                </x-button-box>
            </div>
        </section>
        <section class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors">
            @if($openedPackageList)
                <livewire:packages.index-table/>
            @endif
            @includeWhen($openedCreateReception, 'packages.createEntry')
            @includeWhen($openedCreateShipping, 'packages.createExit')
            @if($openedPackagesDeleted)
                <livewire:packages.deleted-table/>
            @endif
        </section>
    </div>
</div>