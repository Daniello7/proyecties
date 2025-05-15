<div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-3xl gradient-border shadow-xl w-full max-w-4xl">
        <x-header :content="__('Package').' - '.__('Edit '.ucfirst($type))"/>
        <fieldset class="flex flex-row flex-wrap *:w-1/3 gap-8 shadow-md dark:bg-gray-700 p-4 my-4 mx-4 rounded-lg">
            <div class="*:w-full">
                <x-input-label for="entry_time" :value="__('Entry time')"/>
                <div class="relative">
                    <x-text-input wire:model="entry_time" type="datetime-local" name="entry_time" id="entry_time" class="block w-full mt-1 [appearance:textfield] [&::-webkit-calendar-picker-indicator]:hidden"/>
                    <x-svg.calendar-icon class="absolute right-3 top-1/2 transform -translate-y-1/2"/>
                </div>
                <x-input-error :messages="$errors->get('entry_time')"/>
            </div>
            <div class="*:w-full">
                <x-input-label for="exit_time" :value="__('Exit time')"/>
                <div class="relative">
                    <x-text-input wire:model="exit_time" type="datetime-local" name="exit_time" id="exit_time" class="block w-full mt-1 [appearance:textfield] [&::-webkit-calendar-picker-indicator]:hidden"/>
                    <x-svg.calendar-icon class="absolute right-3 top-1/2 transform -translate-y-1/2"/>
                </div>
                <x-input-error :messages="$errors->get('exit_time')"/>
            </div>
            <div class="*:w-full">
                <x-input-label for="agency" :value="__('Agency')"/>
                <x-agency-select wire:model="agency" name="agency" id="agency"/>
                <x-input-error :messages="$errors->get('agency')"/>
            </div>
            <div class="*:w-full">
                <x-input-label for="external_entity" :value="$type == 'exit' ? __('Destination') : __('Sender')"/>
                <x-text-input wire:model="external_entity" type="text" name="external_entity" id="external_entity"/>
                <x-input-error :messages="$errors->get('external_entity')"/>
            </div>
            <div class="*:w-full">
                <x-input-label for="internal_person_id" :value="$type == 'exit' ? __('Sender Employee') : __('Destination Employee')"/>
                <x-person-select wire:model="internal_person_id" id="internal_person_id" name="internal_person_id" class="min-w-max w-60">
                    {{ __('Select an employee') }}
                </x-person-select>
                <x-input-error :messages="$errors->get('internal_person_id')"/>
            </div>
            <div class="*:w-full max-w-28">
                <x-input-label for="package_count" :value="'Nº '.__('Package Count')"/>
                <x-text-input wire:model="package_count" type="number" name="package_count" id="package_count"/>
                <x-input-error :messages="$errors->get('package_count')"/>
            </div>
            <div class="*:w-full">
                <x-input-label for="comment" :value="__('Comment')"/>
                <x-textarea wire:model="comment" cols="30" rows="3" id="comment" name="comment" class="min-w-max w-64"></x-textarea>
                <x-input-error :messages="$errors->get('comment')"/>
            </div>
        </fieldset>
        <div class="flex justify-end gap-4 p-4">
            <x-primary-button wire:click="updatePackage({{ $package_id }})">
                {{ __('Update') }}
                <x-svg.confirm-icon class="w-6 h-6 ml-1"/>
            </x-primary-button>
            <x-secondary-button wire:click="closeModal">
                {{ __('Cancel') }}
                <x-svg.cancel-icon class="w-7 h-7 ml-1"/>
            </x-secondary-button>
        </div>
    </div>
</div>
