<div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-3xl gradient-border shadow-xl w-full max-w-4xl p-4">
        <x-header :content="__('Edit Person').' - '.$person->name.' '.$person->last_name"/>
        <div class="flex flex-col gap-4 mb-2 p-6">
            <fieldset class="flex flex-row flex-wrap gap-8 flex-[3] border shadow-md dark:bg-gray-900 dark:border-gray-700 p-2 rounded-lg">
                <legend class="font-bold italic text-blue-700 dark:text-violet-500 text-xl ml-4 px-2">{{ __('Form') }}</legend>
                <div class="min-w-60 max-w-72">
                    <x-input-label for="document_number" :value="'DNI/NIE'"/>
                    <x-text-input id="document_number" wire:model="document_number" class="block w-full mt-1"/>
                    <x-input-error :messages="$errors->get('document_number')"/>
                </div>
                <div class="min-w-60 max-w-72">
                    <x-input-label for="name" :value="__('Name')"/>
                    <x-text-input id="name" name="name" wire:model="name" class="block w-full mt-1"/>
                    <x-input-error :messages="$errors->get('name')"/>
                </div>
                <div class="min-w-60 max-w-72">
                    <x-input-label for="last_name" :value="__('Last Name')"/>
                    <x-text-input id="last_name" name="last_name" wire:model="last_name" class="block w-full mt-1"/>
                    <x-input-error :messages="$errors->get('last_name')"/>
                </div>
                <div class="min-w-60 max-w-72">
                    <x-input-label for="company" :value="__('Company')"/>
                    <x-text-input id="company" name="company" wire:model="company" class="block w-full mt-1"/>
                    <x-input-error :messages="$errors->get('company')"/>
                </div>
                <div class="min-w-60 max-w-72">
                    <x-input-label for="comment" :value="__('Comment')"/>
                    <x-textarea cols="30" id="comment" wire:model="comment" class="block w-full mt-1"/>
                    <x-input-error :messages="$errors->get('comment')"/>
                </div>
            </fieldset>
            <div class="flex justify-end gap-4 p-4">
                <x-primary-button wire:click="updatePerson">
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
</div>
