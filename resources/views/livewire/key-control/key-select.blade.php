<div>
    <div class="flex flex-row flex-wrap gap-8 shadow-md dark:bg-gray-700 p-4 m-4  rounded-lg">
        <div>
            <x-input-label :value="__('Zone')"/>
            <x-key-zone-select id="zone" wire:model.live="zone" class="min-w-max w-60"/>
        </div>
        <div>
            <x-input-label for="keyId" :value="__('Key')"/>
            <x-key-select id="keyId" :areaId="$zone" wire:model.live="keyId" class="min-w-max w-64"/>
        </div>
    </div>
    <livewire:key-control.index-table/>
</div>
