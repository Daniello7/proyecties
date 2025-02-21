<div class="flex flex-row flex-wrap gap-8 shadow-md dark:bg-gray-700 p-4 my-8 rounded-lg">
    <div>
        <x-input-label for="person_id" :value="__('Person')"/>
        <x-person-select id="person_id" name="person_id" class="min-w-max w-60">
            {{ __('Select a person') }}
        </x-person-select>
        <x-input-error :messages="$errors->get('person_id')"/>
    </div>
    <div>
        <x-input-label :value="__('Zone')"/>
        <x-key-zone-select wire:model.live="zone" class="min-w-max w-60"/>
    </div>
    <div>
        <x-input-label for="key_id" :value="__('Key')"/>
        <x-key-select id="key_id" name="key_id" :zone="$zone" class="min-w-max w-64"/>
        <x-input-error :messages="$errors->get('key_id')"/>
    </div>
    <div>
        <x-input-label for="comment" :value="__('Comment')"/>
        <x-textarea cols="30" rows="3" id="comment" name="comment" class="min-w-max w-64"/>
        <x-input-error :messages="$errors->get('comment')"/>
    </div>
</div>
