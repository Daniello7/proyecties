<div class="flex flex-col md:flex-row gap-4 p-4">
    {{-- Person Info --}}
    <fieldset class="flex-1 border dark:bg-gray-900 dark:border-gray-700 p-2 rounded-lg shadow-md">
        <legend class="font-bold italic text-blue-700 dark:text-violet-500 text-xl ml-4 px-2">{{ __('Data') }}</legend>
        <div class="flex flex-row gap-2">
            <h3 class="font-bold text-blue-600 dark:text-pink-500">DNI:</h3>
            <p>{{ $internalPerson->person->document_number }}</p>
        </div>
        <div class="flex flex-row gap-2">
            <h3 class="font-bold text-blue-600 dark:text-pink-500">{{ __('Name') }}:</h3>
            <p>{{ $internalPerson->person->name }}</p>
        </div>
        <div class="flex flex-row gap-2">
            <h3 class="font-bold text-blue-600 dark:text-pink-500">{{ __('Last Name') }}:</h3>
            <p>{{ $internalPerson->person->last_name }}</p>
        </div>
        <div class="flex flex-row gap-2">
            <h3 class="font-bold text-blue-600 dark:text-pink-500">{{ __('Email') }}:</h3>
            <p>{{ $internalPerson->email }}</p>
        </div>
        <div class="flex flex-row gap-2">
            <h3 class="font-bold text-blue-600 dark:text-pink-500">{{ __('Phone Number') }}:</h3>
            <p>{{ $internalPerson->phone }}</p>
        </div>
        <div class="flex flex-row gap-2">
            <h3 class="font-bold text-blue-600 dark:text-pink-500">{{ __('Contract') }}:</h3>
            <p>{{ $internalPerson->contract_type }}</p>
        </div>
        <div class="flex flex-row gap-2">
            <h3 class="font-bold text-blue-600 dark:text-pink-500">{{ __('Hiring date') }}:</h3>
            <p>{{ $internalPerson->hired_at }}</p>
        </div>
        <div class="flex flex-row gap-2">
            <h3 class="font-bold text-blue-600 dark:text-pink-500">{{ __('Address') }}:</h3>
            <p>{{ $internalPerson->address }}</p>
        </div>
        <div class="flex flex-row gap-2">
            <h3 class="font-bold text-blue-600 dark:text-pink-500">{{ __('Country') }}:</h3>
            <p>{{ $internalPerson->country }}</p>
        </div>
        <div class="flex flex-row gap-2">
            <h3 class="font-bold text-blue-600 dark:text-pink-500">{{ __('City') }}:</h3>
            <p>{{ $internalPerson->city }}</p>
        </div>
        <div class="flex flex-row gap-2">
            <h3 class="font-bold text-blue-600 dark:text-pink-500">{{ __('Province') }}:</h3>
            <p>{{ $internalPerson->province }}</p>
        </div>
        <div class="flex flex-row gap-2">
            <h3 class="font-bold text-blue-600 dark:text-pink-500">{{ __('Zip Code') }}:</h3>
            <p>{{ $internalPerson->zip_code }}</p>
        </div>
    </fieldset>
    {{-- Comments --}}
    <fieldset class="flex-1 border dark:bg-gray-900 dark:border-gray-700 p-2 rounded-lg shadow-md">
        <legend class="font-bold italic text-blue-700 dark:text-violet-500 text-xl ml-4 px-2">{{ __('Comments') }}</legend>
        <p>{{ $internalPerson->person->comment }}</p>
    </fieldset>
</div>
