<div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-3xl gradient-border shadow-xl w-full max-w-4xl">
        <div class="text-gray-800 dark:text-gray-100">
            <div>
                <h2 class="text-xl font-semibold p-4 custom-gradient-text uppercase">
                    {{ __('Edit Entry').' - '. $entry->person->name.' '.$entry->person->last_name }}
                </h2>
                <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
            </div>
            <div class="flex flex-col gap-4 mb-2 p-6">
                {{-- Person Info --}}
                <fieldset class="flex flex-row flex-wrap gap-8 flex-1 border shadow-md dark:bg-gray-900 dark:border-gray-700 p-2 rounded-lg">
                    <legend class="font-bold italic text-blue-700 dark:text-violet-500 text-xl ml-4 px-2">{{ __('Information') }}</legend>
                    <div class="flex gap-2">
                        <h3 class="font-bold text-blue-600 dark:text-pink-500">DNI:</h3>
                        <p class="inline-block">{{ $entry->person->document_number }}</p>
                    </div>
                    <div class="flex gap-2">
                        <h3 class="font-bold text-blue-600 dark:text-pink-500">{{ __('Name') }}:</h3>
                        <p class="inline-block">{{ $entry->person->name.' '.$entry->person->last_name }}</p>
                    </div>
                    <div class="flex gap-2">
                        <h3 class="font-bold text-blue-600 dark:text-pink-500">{{ __('Company') }}:</h3>
                        <p class="inline-block">{{ $entry->person->company }}</p>
                    </div>
                </fieldset>
                {{-- Form Fields --}}
                <fieldset class="flex flex-row flex-wrap gap-8 flex-1 border shadow-md dark:bg-gray-900 dark:border-gray-700 p-4 rounded-lg">
                    <legend class="font-bold italic text-blue-700 dark:text-violet-500 text-xl ml-4 px-2">{{ __('Form') }}</legend>
                    <div class="flex flex-col gap-4">
                        <div>
                            <x-input-label for="reason" :value="__('Reason')"/>
                            <x-reason-select id="reason" wire:model="reason" class="block w-full mt-1"/>
                            <x-input-error :messages="$errors->get('reason')"/>
                        </div>
                        <div>
                            <x-input-label for="internal_person_id" :value="__('Contact')"/>
                            <x-person-select id="internal_person_id" wire:model="internal_person_id" class="block w-full mt-1"/>
                            <x-input-error :messages="$errors->get('internal_person_id')"/>
                        </div>
                    </div>
                    <!-- Time Columns -->
                    <div class="flex flex-col gap-4">
                        <div>
                            <x-input-label for="arrival_time" :value="__('Arrival')"/>
                            <div class="relative">
                                <x-text-input id="arrival_time" wire:model="arrival_time" type="datetime-local" class="block w-full mt-1 [appearance:textfield] [&::-webkit-calendar-picker-indicator]:hidden"/>
                                <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 h-5 w-5 fill-blue-700 dark:fill-pink-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 011 1v1h6V3a1 1 0 112 0v1h1a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2h1V3a1 1 0 011-1zm-2 6v8h12V8H4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <x-input-error :messages="$errors->get('arrival_time')"/>
                        </div>
                        <div>
                            <x-input-label for="entry_time" :value="__('Entry')"/>
                            <div class="relative">
                                <x-text-input id="entry_time" wire:model="entry_time" type="datetime-local" class="block w-full mt-1 [appearance:textfield] [&::-webkit-calendar-picker-indicator]:hidden"/>
                                <x-svg.calendar-icon class="absolute right-3 top-1/2 transform -translate-y-1/2"/>
                            </div>
                            <x-input-error :messages="$errors->get('entry_time')"/>
                        </div>
                        <div>
                            <x-input-label for="exit_time" :value="__('Exit')"/>
                            <div class="relative">
                                <x-text-input id="exit_time" wire:model="exit_time" type="datetime-local" class="block w-full mt-1 [appearance:textfield] [&::-webkit-calendar-picker-indicator]:hidden"/>
                                <x-svg.calendar-icon class="absolute right-3 top-1/2 transform -translate-y-1/2"/>
                            </div>
                            <x-input-error :messages="$errors->get('exit_time')"/>
                        </div>
                    </div>
                    <div>
                        <x-input-label for="comment" :value="__('Comment')"/>
                        <x-textarea id="comment" wire:model="comment" cols="30" rows="5"></x-textarea>
                        <x-input-error :messages="$errors->get('comment')"/>
                    </div>
                </fieldset>
            </div>
            <div class="flex justify-end gap-4 p-4">
                <x-primary-button wire:click="updatePersonEntry">
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
