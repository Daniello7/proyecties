<div>
    <h2 class="text-xl font-semibold p-4 custom-gradient-text uppercase">
        {{ __('New Person').' - '.__('Form') }}
    </h2>
    <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
</div>
<div class="flex flex-row flex-wrap justify-evenly gap-8 *:flex-1 shadow-md dark:bg-gray-700 p-4 my-8 mx-12 rounded-lg">
    <div class="min-w-60 max-w-72">
        <x-input-label for="document_number" :value="'DNI/NIE'"/>
        <x-text-input id="document_number" name="document_number" :old-document_number="old('document_number', $person->document_number ?? '' )" class="block w-full mt-1"/>
        <x-input-error :messages="$errors->get('document_number')"/>
    </div>
    <div class="min-w-60 max-w-72">
        <x-input-label for="name" :value="__('Name')"/>
        <x-text-input id="name" name="name" :old-name="old('name', $person->name ?? '' )" class="block w-full mt-1"/>
        <x-input-error :messages="$errors->get('name')"/>
    </div>
    <div class="min-w-60 max-w-72">
        <x-input-label for="last_name" :value="__('Last Name')"/>
        <x-text-input id="last_name" name="last_name" :old-last_name="old('last_name', $person->last_name ?? '' )" class="block w-full mt-1"/>
        <x-input-error :messages="$errors->get('last_name')"/>
    </div>
    <div class="min-w-60 max-w-72">
        <x-input-label for="company" :value="__('Company')"/>
        <x-text-input id="company" name="company" :old-document_number="old('company', $person->company ?? '' )" class="block w-full mt-1"/>
        <x-input-error :messages="$errors->get('company')"/>
    </div>
</div>