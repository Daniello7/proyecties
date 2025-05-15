<div>
    <x-header :content="__('Package').' - '.__('New Shipping')"/>
    <fieldset class="flex flex-row flex-wrap *:w-1/3 gap-8 shadow-md dark:bg-gray-700 p-4 my-4 mx-4 rounded-lg">
        <legend class="font-bold italic text-blue-700 dark:text-violet-500 text-xl ml-4 px-2">{{ __('Form') }}</legend>
        <div class="*:w-full">
            <x-input-label for="agency" :value="__('Agency')"/>
            <x-agency-select wire:model="agency" id="agency"/>
            <x-input-error :messages="$errors->get('agency')"/>
        </div>
        <div class="*:w-full">
            <x-input-label for="external_entity" :value="__('Destination')"/>
            <x-text-input type="text" wire:model="external_entity" id="external_entity"/>
            <x-input-error :messages="$errors->get('external_entity')"/>
        </div>
        <div class="*:w-full">
            <x-input-label for="internal_person_id" :value="__('Sender Employee')"/>
            <x-person-select wire:model="internal_person_id" id="internal_person_id" class="min-w-max w-60">
                {{ __('Select an employee') }}
            </x-person-select>
            <x-input-error :messages="$errors->get('internal_person_id')"/>
        </div>
        <div class="*:w-full max-w-28">
            <x-input-label for="package_count" :value="'Nº '.__('Package Count')"/>
            <x-text-input type="number" wire:model="package_count" id="package_count"/>
            <x-input-error :messages="$errors->get('package_count')"/>
        </div>
        <div class="*:w-full">
            <x-input-label for="comment" :value="__('Comment')"/>
            <x-textarea wire:model="comment" id="comment" cols="30" rows="3" class="min-w-max w-64"/>
            <x-input-error :messages="$errors->get('comment')"/>
        </div>
    </fieldset>
    <x-primary-button wire:click="storePackage('exit')" class="m-4">
        {{ __('Save') }}
        <x-svg.confirm-icon class="w-6 h-6 ml-2"/>
    </x-primary-button>
</div>
