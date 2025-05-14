<div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-3xl gradient-border shadow-xl w-full max-w-4xl">
        <x-header :content="__('New Entry').' - '. $person->name.' '.$person->last_name"/>
        <div class="flex flex-col gap-4 mb-2 p-6">
            {{-- Person Info --}}
            <fieldset class="flex flex-row flex-wrap gap-8 flex-1 border shadow-md dark:bg-gray-900 dark:border-gray-700 p-2 rounded-lg">
                <legend class="font-bold italic text-blue-700 dark:text-violet-500 text-xl ml-4 px-2">{{ __('Person Info') }}</legend>
                <div class="flex gap-2">
                    <h3 class="font-bold text-blue-600 dark:text-pink-500">DNI:</h3>
                    <p class="inline-block">{{ $person->document_number }}</p>
                </div>
                <div class="flex gap-2">
                    <h3 class="font-bold text-blue-600 dark:text-pink-500">{{ __('Name') }}:</h3>
                    <p class="inline-block">{{ $person->name }}</p><br>
                </div>
                <div class="flex gap-2">
                    <h3 class="font-bold text-blue-600 dark:text-pink-500">{{ __('Last Name') }}:</h3>
                    <p class="inline-block">{{ $person->last_name }}</p><br>
                </div>
                <div class="flex gap-2">
                    <h3 class="font-bold text-blue-600 dark:text-pink-500">{{ __('Company') }}:</h3>
                    <p class="inline-block">{{ $person->company }}</p>
                </div>
            </fieldset>
            {{-- Form Fields --}}
            <fieldset class="flex flex-row flex-wrap gap-8 flex-[3] border shadow-md dark:bg-gray-900 dark:border-gray-700 p-2 rounded-lg">
                <legend class="font-bold italic text-blue-700 dark:text-violet-500 text-xl ml-4 px-2">{{ __('Form') }}</legend>
                <div>
                    <x-input-label for="internal_person_id" :value="__('Contact')"/>
                    <x-person-select wire:model="internal_person_id" id="internal_person_id" class="block w-full mt-1">
                        {{ __('Select a contact') }}
                    </x-person-select>
                    <x-input-error :messages="$errors->get('internal_person_id')"/>
                    <div class="mt-4">
                        <x-input-label for="notify" class="inline-block ml-2">{{ __('Notify Contact') }}</x-input-label>
                        <input id="notify" wire:model="notify" type="checkbox" class="cursor-pointer rounded hover:ring-2 dark:checked:bg-blue-600 dark:bg-transparent dark:border-gray-500"/>
                    </div>
                </div>
                <div>
                    <x-input-label for="reason" :value="__('Reason')"/>
                    <x-reason-select wire:model="reason" id="reason" class="block w-full mt-1"/>
                    <x-input-error :messages="$errors->get('reason')"/>
                    <div class="mt-4">
                        <x-input-label for="enter" class="inline-block ml-2">{{ __('Enter') }}</x-input-label>
                        <input id="enter" wire:model="enter" type="checkbox" class="cursor-pointer rounded hover:ring-2 dark:checked:bg-blue-600 dark:bg-transparent dark:border-gray-500"/>
                    </div>
                </div>
                <div>
                    <x-input-label for="comment" :value="__('Comment')"/>
                    <x-textarea wire:model="comment" id="comment" cols="30" rows="3"></x-textarea>
                    <x-input-error :messages="$errors->get('comment')"/>
                </div>
            </fieldset>
        </div>
        <div class="flex justify-end gap-4 p-4">
            <x-primary-button wire:click="storePersonEntry">
                {{ __('Save') }}
                <x-svg.confirm-icon class="w-6 h-6 ml-1"/>
            </x-primary-button>
            <x-secondary-button wire:click="closeModal">
                {{ __('Cancel') }}
                <x-svg.cancel-icon class="w-7 h-7 ml-1"/>
            </x-secondary-button>
        </div>
    </div>
</div>

