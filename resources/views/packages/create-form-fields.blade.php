@props(['isExit' => false, 'isEditView' => false])
<div>
    <div class="flex flex-row flex-wrap *:w-1/3 gap-8 shadow-md dark:bg-gray-700 p-4 my-8 rounded-lg">
        @if($isEditView)
            <div class="*:w-full">
                <x-input-label for="entry_time" :value="__('Entry time')"/>
                <div class="relative">
                    <x-text-input type="datetime-local" name="entry_time" id="entry_time" :value="old('entry_time', $package->entry_time ?? '')" class="block w-full mt-1 [appearance:textfield] [&::-webkit-calendar-picker-indicator]:hidden"/>
                    <x-svg.calendar-icon class="absolute right-3 top-1/2 transform -translate-y-1/2"/>
                </div>
                <x-input-error :messages="$errors->get('entry_time')"/>
            </div>
            <div class="*:w-full">
                <x-input-label for="exit_time" :value="__('Exit time')"/>
                <div class="relative">
                    <x-text-input type="datetime-local" name="exit_time" id="exit_time" :value="old('exit_time', $package->exit_time ?? '')" class="block w-full mt-1 [appearance:textfield] [&::-webkit-calendar-picker-indicator]:hidden"/>
                    <x-svg.calendar-icon class="absolute right-3 top-1/2 transform -translate-y-1/2"/>
                </div>
                <x-input-error :messages="$errors->get('exit_time')"/>
            </div>
        @endif
        <div class="*:w-full">
            <x-input-label for="agency" :value="__('Agency')"/>
            <x-agency-select name="agency" id="agency" :old-agency="old('agency', $package->agency ?? '')"/>
            <x-input-error :messages="$errors->get('agency')"/>
        </div>
        <div class="*:w-full">
            <x-input-label for="external_entity" :value="$isExit ? __('Destination') :__('Sender')"/>
            <x-text-input type="text" name="external_entity" id="external_entity" :value="old('external_entity', $package->external_entity ?? '')"/>
            <x-input-error :messages="$errors->get('external_entity')"/>
        </div>
        <div class="*:w-full">
            <x-input-label for="internal_person_id" :value="$isExit ? __('Sender Employee') : __('Destination Employee')"/>
            <x-person-select id="internal_person_id" name="internal_person_id" :old-contact="old('internal_person_id', $package->internal_person_id ?? '')" class="min-w-max w-60">
                {{ __('Select an employee') }}
            </x-person-select>
            <x-input-error :messages="$errors->get('internal_person_id')"/>
            @if(!$isExit && !$isEditView)
                <div class="mt-4">
                    <input id="notify" name="notify" type="checkbox" class="cursor-pointer rounded hover:ring-2 dark:checked:bg-blue-600 dark:bg-transparent dark:border-gray-500"/>
                    <x-input-label for="notify" class="inline-block ml-2">{{ __('Notify Contact') }}</x-input-label>
                </div>
            @endif
        </div>
        <div class="*:w-full max-w-28">
            <x-input-label for="package_count" :value="'Nº '.__('Package Count')"/>
            <x-text-input type="number" name="package_count" id="package_count" :value="old('package_count', $package->package_count ?? '')"/>
            <x-input-error :messages="$errors->get('package_count')"/>
        </div>
        <div class="*:w-full">
            <x-input-label for="comment" :value="__('Comment')"/>
            <x-textarea cols="30" rows="3" id="comment" name="comment" class="min-w-max w-64">
                {{ old('comment', $package->comment ?? '') }}
            </x-textarea>
            <x-input-error :messages="$errors->get('comment')"/>
        </div>
    </div>
</div>
