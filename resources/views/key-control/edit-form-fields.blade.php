<div class="flex flex-row flex-wrap gap-8 shadow-md dark:bg-gray-700 p-4 m-4 rounded-lg">
    <div>
        <x-input-label for="zone" :value="__('Zone')"/>
        <p class="dark:text-gray-300 bg-white dark:bg-gray-900 py-2 px-4  border border-gray-300 dark:border-gray-700 rounded-md shadow-sm">
            {{ "{$exitKey->key->area_id} - ". __($exitKey->key->area->name) }}
        </p>
    </div>
    <div>
        <x-input-label for="key_id" :value="__('Key')"/>
        <p class="dark:text-gray-300 bg-white dark:bg-gray-900 py-2 px-4  border border-gray-300 dark:border-gray-700 rounded-md shadow-sm">
            {{ "{$exitKey->key->area_id}.{$exitKey->key->area_key_number} - {$exitKey->key->name}" }}
        </p>
    </div>
    <div>
        <x-input-label for="person_id" :value="__('Person')"/>
        <x-person-select wire:model="person_id" :include-external="true" id="person_id" name="person_id" :old-contact="$person_id" class="min-w-max w-60">
            {{ __('Select a person') }}
        </x-person-select>
        <x-input-error :messages="$errors->get('person_id')"/>
    </div>
    <div>
        <x-input-label for="entry_time" :value="__('Entry')"/>
        <div class="relative">
            <x-text-input wire:model="entry_time" id="entry_time" name="entry_time" type="datetime-local" class="block w-full mt-1 [appearance:textfield] [&::-webkit-calendar-picker-indicator]:hidden"/>
            <x-svg.calendar-icon class="absolute right-3 top-1/2 transform -translate-y-1/2"/>
        </div>
        <x-input-error :messages="$errors->get('entry_time')"/>
    </div>
    <div>
        <x-input-label for="exit_time" :value="__('Exit')"/>
        <div class="relative">
            <x-text-input wire:model="exit_time" id="exit_time" name="exit_time" type="datetime-local" class="block w-full mt-1 [appearance:textfield] [&::-webkit-calendar-picker-indicator]:hidden"/>
            <x-svg.calendar-icon class="absolute right-3 top-1/2 transform -translate-y-1/2"/>
        </div>
        <x-input-error :messages="$errors->get('exit_time')"/>
    </div>
    <div>
        <x-input-label for="comment" :value="__('Comment')"/>
        <x-textarea wire:model="comment" cols="30" rows="3" id="comment" name="comment" class="min-w-max w-64"/>
        <x-input-error :messages="$errors->get('comment')"/>
    </div>
</div>
