<div class="flex flex-col md:flex-row gap-4 p-4">
    {{-- Person Info --}}
    <fieldset class="flex-1 border dark:bg-gray-900 dark:border-gray-700 p-2 rounded-lg">
        <legend class="font-bold italic text-blue-700 dark:text-violet-500 text-xl ml-4 px-2">{{ __('Data') }}</legend>
        <div class="flex flex-row gap-2">
            <h3 class="font-bold text-blue-600 dark:text-pink-500">DNI:</h3>
            <p>{{ $person->document_number }}</p>
        </div>
        <div class="flex flex-row gap-2">
            <h3 class="font-bold text-blue-600 dark:text-pink-500">{{ __('Name') }}:</h3>
            <p>{{ $person->name.' '.$person->last_name }}</p>
        </div>
        <div class="flex flex-row gap-2">
            <h3 class="font-bold text-blue-600 dark:text-pink-500">{{ __('Company') }}:</h3>
            <p>{{ $person->company }}</p>
        </div>
    </fieldset>
    {{-- Comments --}}
    <fieldset class="flex-1 border dark:bg-gray-900 dark:border-gray-700 p-2 rounded-lg">
        <legend class="font-bold italic text-blue-700 dark:text-violet-500 text-xl ml-4 px-2">{{ __('Comments') }}</legend>
        <p>{{ $person->comment->content }}</p>
    </fieldset>
</div>
