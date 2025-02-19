<div>
    <h2 class="text-xl font-semibold p-4 custom-gradient-text uppercase">
        {{ __('New Entry').' - '. $person->name.' '.$person->last_name }}
    </h2>
    <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
</div>
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
            <p class="inline-block">{{ $person->name.' '.$person->last_name }}</p><br>
        </div>
        <div class="flex gap-2">
            <h3 class="font-bold text-blue-600 dark:text-pink-500">{{ __('Company') }}:</h3>
            <p class="inline-block">{{ $person->company }}</p>
        </div>
        <input type="hidden" name="person_id" value="{{ $person->id }}">
    </fieldset>
    {{-- Form Fields --}}
    <fieldset class="flex flex-row flex-wrap gap-8 flex-[3] border shadow-md dark:bg-gray-900 dark:border-gray-700 p-2 rounded-lg">
        <legend class="font-bold italic text-blue-700 dark:text-violet-500 text-xl ml-4 px-2">{{ __('Form') }}</legend>
        <div>
            <x-input-label for="internal_person_id" :value="__('Contact')"/>
            <x-person-select id="internal_person_id" name="internal_person_id" :old-contact="old('internal_person_id', $lastEntry->internal_person_id ?? '')" class="block w-full mt-1"/>
            <x-input-error :messages="$errors->get('internal_person_id')"/>
            <div class="mt-4">
                <input id="notify" name="notify" type="checkbox" class="cursor-pointer rounded hover:ring-2 dark:checked:bg-blue-600 dark:bg-transparent dark:border-gray-500"/>
                <x-input-label for="notify" class="inline-block ml-2">{{ __('Notify Contact') }}</x-input-label>
            </div>
        </div>
        <div>
            <x-input-label for="reason" :value="__('Reason')"/>
            <x-reason-select id="reason" name="reason" :old-reason="old('reason', $lastEntry->reason ?? '' )" class="block w-full mt-1"/>
            <x-input-error :messages="$errors->get('reason')"/>
        </div>
        <div>
            <x-input-label for="comment" :value="__('Comment')"/>
            <x-textarea id="comment" name="comment" cols="30" rows="5"></x-textarea>
            <x-input-error :messages="$errors->get('comment')"/>
        </div>
    </fieldset>
</div>
