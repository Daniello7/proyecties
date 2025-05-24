<div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
    <div class="p-4 w-full max-w-5xl overflow-y-auto max-h-screen">
        <section class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-3xl gradient-border">
            <x-header content="{{ __('Key Control').' - '. __('New Key') }}"/>
            <div class="flex flex-col gap-4 mb-2 p-6">
                <fieldset class="flex flex-row flex-wrap gap-8 flex-1 border shadow-md dark:bg-gray-900 dark:border-gray-700 p-4 rounded-lg">
                    <legend class="font-bold italic text-blue-700 dark:text-violet-500 text-xl ml-4 px-2">{{ __('Form') }}</legend>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 w-full">
                        <div>
                            <x-input-label for="area_id" :value="__('Area_id')"/>
                            <x-key-zone-select id="area_id" wire:model="area_id" class="block w-full mt-1"/>
                            <x-input-error :messages="$errors->get('area_id')"/>
                        </div>
                        <div>
                            <x-input-label for="name" :value="__('Name')"/>
                            <x-text-input id="name" wire:model="name" class="block w-full mt-1"/>
                            <x-input-error :messages="$errors->get('name')"/>
                        </div>
                    </div>
                </fieldset>
                <div class="flex justify-end gap-4 p-4">
                    <x-primary-button wire:click="storeKey">
                        {{ __('Save') }}
                        <x-svg.confirm-icon class="w-6 h-6 ml-1"/>
                    </x-primary-button>
                    <x-secondary-button wire:click="closeModal">
                        {{ __('Cancel') }}
                        <x-svg.cancel-icon class="w-7 h-7 ml-1"/>
                    </x-secondary-button>
                </div>
            </div>
        </section>
    </div>
</div>