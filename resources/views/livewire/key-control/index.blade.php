@php use App\Models\Key;use App\Models\KeyControl; @endphp
<div class="flex flex-col gap-8 mx-auto sm:px-6 lg:px-8">
    <section class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors z-10">
        <x-header :content="__('Options')"><x-session-status flash="key-status"/></x-header>
        <div class="py-6 flex flex-row gap-4 justify-evenly">
            @can('view-any', Key::class)
                <x-button-box wire:click="openKeyTable">
                    <x-svg.key-icon class="w-8 h-8 stroke-blue-600"/>
                    {{ __('Keys') }}
                </x-button-box>
            @endcan
            <x-button-box wire:click="openExitKeysTable">
                <x-svg.key-control-icon class="w-8 h-8 fill-blue-600"/>
                {{ __('Latest Records') }}
            </x-button-box>
            @can('create', KeyControl::class)
                <x-button-box wire:click="openCreateExitKey">
                    <x-svg.add-key-icon class="w-8 h-8 stroke-blue-600"/>
                    {{ __('New Exit') }}
                </x-button-box>
            @endcan
            <x-button-box wire:click="openSearchKey">
                <x-svg.search-icon class="w-8 h-8 stroke-blue-600"/>
                {{ __('Search for Key') }}
            </x-button-box>
        </div>
    </section>
    <section class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors">
        @if($openedExitKeysTable)
            <x-header :content="__('Key Control').' - '.__('Latest Records')"/>
            <livewire:key-control.index-table/>
        @endif
        @includeWhen($openedCreateExitKey, 'key-control.create')
        @includeWhen($openedSearchKey, 'keys.index')
        @if($openedKeyTable)
            <livewire:key-control.key-table/>
        @endif
    </section>
</div>