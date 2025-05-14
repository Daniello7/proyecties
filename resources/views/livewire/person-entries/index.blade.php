<div class="py-8">
    <div class="flex flex-col gap-8 mx-auto sm:px-6 lg:px-8">
        <section class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors z-10">
            <x-header :content="__('Options')"/>
            <div class="py-6 flex flex-row gap-4 justify-evenly">
                <x-button-box wire:click="openLastestEntriesTable()">{{ __('Latest Records') }}
                    <x-svg.entry-icon class="w-9 h-9"/>
                </x-button-box>
                <x-button-box wire:click="openPersonListTable()">{{ __('Person List') }}
                    <x-svg.people-icon class="w-9 h-9 stroke-blue-600 dark:stroke-pink-500"/>
                </x-button-box>
            </div>
        </section>
        <section class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-4 shadow-lg transition-colors">
            @if($openedLastestEntriesTable)
                @livewire('person-entries.index-table')
            @endif
            @if($openedPersonListTable)
                @livewire('person-table')
            @endif
        </section>
    </div>
</div>
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('openRulesPdf', ({url}) => {
            window.open(url, '_blank');
        });
    });
</script>
