<div class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 transition-colors">
    <x-header :content="__('Key Control').' - '.__('New Exit')">
        <x-session-status flash="key-status" class="p-4"/>
    </x-header>
    <section class="flex flex-row flex-wrap gap-8 shadow-md dark:bg-gray-700 p-4 m-4  rounded-lg">
        <div>
            <x-input-label for="areaId" :value="__('Area')"/>
            <x-key-zone-select id="areaId" wire:model.live="areaId" class="min-w-max w-60"/>
        </div>
        <div>
            <x-input-label for="key_id" :value="__('Key')"/>
            <x-key-select id="key_id" :areaId="$areaId" wire:model.live="key_id" class="min-w-max w-64"/>
        </div>
        <div>
            <x-input-label for="person_id" :value="__('Person')"/>
            <x-person-select :include-external="true" id="person_id" wire:model="person_id" class="min-w-max w-60">
                {{ __('Select a person') }}
            </x-person-select>
            <x-input-error :messages="$errors->get('person_id')"/>
        </div>
        <div>
            <x-input-label for="comment" :value="__('Comment')"/>
            <x-textarea cols="30" rows="3" id="comment" wire:model="comment" class="min-w-max w-64"/>
            <x-input-error :messages="$errors->get('comment')"/>
        </div>
    </section>
    <x-primary-button class="m-4" wire:click="storeExitKey">
        {{ __('Save') }}
        <x-svg.confirm-icon class="w-7 h-7 ml-2"/>
    </x-primary-button>
</div>
