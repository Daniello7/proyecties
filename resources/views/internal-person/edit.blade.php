<div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
    <div class="p-4 w-full max-w-5xl overflow-y-auto max-h-screen">
        <div class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-3xl gradient-border">
            <x-header content="{{ __('Edit').' '.__('Internal Staff').' - '. $employer->person->name.' '.$employer->person->last_name }}"/>
            <div class="flex flex-col gap-4 mb-2 p-6">
                {{-- Person Info --}}
                <fieldset class="flex flex-row flex-wrap gap-8 flex-1 border shadow-md dark:bg-gray-900 dark:border-gray-700 p-2 rounded-lg">
                    <legend class="font-bold italic text-blue-700 dark:text-violet-500 text-xl ml-4 px-2">{{ __('Information') }}</legend>
                    <div class="flex gap-2">
                        <h3 class="font-bold text-blue-600 dark:text-pink-500">DNI:</h3>
                        <p class="inline-block">{{ $employer->person->document_number }}</p>
                    </div>
                    <div class="flex gap-2">
                        <h3 class="font-bold text-blue-600 dark:text-pink-500">{{ __('Name') }}:</h3>
                        <p class="inline-block">{{ $employer->person->name.' '.$employer->person->last_name }}</p>
                    </div>
                </fieldset>
                {{-- Form Fields --}}
                <fieldset class="flex flex-row flex-wrap gap-8 flex-1 border shadow-md dark:bg-gray-900 dark:border-gray-700 p-4 rounded-lg">
                    <legend class="font-bold italic text-blue-700 dark:text-violet-500 text-xl ml-4 px-2">{{ __('Form') }}</legend>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 w-full">
                        <div>
                            <x-input-label for="address" :value="__('Address')"/>
                            <x-text-input id="address" wire:model="address" class="block w-full mt-1"/>
                            <x-input-error :messages="$errors->get('address')"/>
                        </div>
                        <div>
                            <x-input-label for="zip_code" :value="__('Zip Code')"/>
                            <x-text-input id="zip_code" wire:model="zip_code" class="block w-full mt-1"/>
                            <x-input-error :messages="$errors->get('zip_code')"/>
                        </div>
                        <div>
                            <x-input-label for="country" :value="__('Country')"/>
                            <x-text-input id="country" wire:model="country" class="block w-full mt-1"/>
                            <x-input-error :messages="$errors->get('country')"/>
                        </div>
                        <div>
                            <x-input-label for="city" :value="__('City')"/>
                            <x-text-input id="city" wire:model="city" class="block w-full mt-1"/>
                            <x-input-error :messages="$errors->get('city')"/>
                        </div>
                        <div>
                            <x-input-label for="email" :value="__('Email')"/>
                            <x-text-input type="email" id="email" wire:model="email" class="block w-full mt-1"/>
                            <x-input-error :messages="$errors->get('email')"/>
                        </div>
                        <div>
                            <x-input-label for="phone" :value="__('Phone Number')"/>
                            <x-text-input id="phone" wire:model="phone" class="block w-full mt-1"/>
                            <x-input-error :messages="$errors->get('phone')"/>
                        </div>
                        <div>
                            <x-input-label for="contract_type" :value="__('Contract')"/>
                            <x-text-input id="contract_type" wire:model="contract_type" class="block w-full mt-1"/>
                            <x-input-error :messages="$errors->get('contract_type')"/>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="flex justify-end gap-4 p-4">
                <x-primary-button wire:click="updateInternalPerson">
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
