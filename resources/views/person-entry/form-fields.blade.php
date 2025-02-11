<div class="flex flex-row gap-4 mb-2">
    {{-- Person Info --}}
    <fieldset class="flex-1 border dark:bg-gray-900 dark:border-gray-700 p-2 rounded-lg">
        <legend class="px-2">{{ __('Person Info') }}</legend>
        <span class="font-bold">DNI:</span>
        <p class="inline-block">{{ $personEntry->person->document_number }}</p><br> <span class="font-bold">{{ __('Name') }}:</span>
        <p class="inline-block">{{ $personEntry->person->name.' '.$personEntry->person->last_name }}</p><br>
        <span class="font-bold">{{ __('Company') }}:</span>
        <p class="inline-block">{{ $personEntry->person->company }}</p>
        <input type="hidden" name="person_id" value="{{ $personEntry->person_id }}">
    </fieldset>
    {{-- Form Fields --}}
    <fieldset class="flex-1 lg:flex-[3] flex flex-col lg:flex-row justify-evenly gap-2 rounded">
        <div>
            <x-input-label for="internal_person_id" :value="__('Contact')"/>
            <x-person-select id="internal_person_id" name="internal_person_id" :old-contact="old('internal_person_id', $personEntry->internal_person_id ?? '')" class="block w-full mt-1"/>
            <x-input-error :messages="$errors->get('internal_person_id')"/>
            <div class="mt-4">
                <input id="notify" name="notify" type="checkbox" class="cursor-pointer rounded hover:ring-2 dark:checked:bg-blue-600 dark:bg-transparent dark:border-gray-500"/>
                <x-input-label for="notify" class="inline-block ml-2">{{ __('Notify Contact') }}</x-input-label>
            </div>
        </div>
        <div>
            <x-input-label for="reason" :value="__('Reason')"/>
            <x-reason-select id="reason" name="reason" type="text" :old-reason="old('reason', $personEntry->reason)" class="block w-full mt-1"/>
            <x-input-error :messages="$errors->get('reason')"/>
        </div>
        <div>
            <x-input-label for="comment" :value="__('Comment')"/>
            <x-textarea id="comment" name="comment" cols="30" rows="5"></x-textarea>
            <x-input-error :messages="$errors->get('comment')"/>
        </div>
    </fieldset>
</div>
