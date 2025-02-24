@if(request()->routeIs('person.edit'))
    <x-header :content="__('Edit Person'.old('name', isset($person->name) ? ' - '.$person->name : '' ))"/>
@else
    <x-header :content="__('New External Person').old('name', isset($person->name) ? ' - '.$person->name : '' )"/>
@endif
<div class="flex flex-row flex-wrap justify-evenly gap-8 *:flex-1 shadow-md dark:bg-gray-700 p-4 my-8 mx-12 rounded-lg">
    <div class="min-w-60 max-w-72">
        <x-input-label for="document_number" :value="'DNI/NIE'"/>
        <x-text-input id="document_number" name="document_number" :value="old('document_number', $person->document_number ?? '' )" class="block w-full mt-1"/>
        <x-input-error :messages="$errors->get('document_number')"/>
    </div>
    <div class="min-w-60 max-w-72">
        <x-input-label for="name" :value="__('Name')"/>
        <x-text-input id="name" name="name" :value="old('name', $person->name ?? '' )" class="block w-full mt-1"/>
        <x-input-error :messages="$errors->get('name')"/>
    </div>
    <div class="min-w-60 max-w-72">
        <x-input-label for="last_name" :value="__('Last Name')"/>
        <x-text-input id="last_name" name="last_name" :value="old('last_name', $person->last_name ?? '' )" class="block w-full mt-1"/>
        <x-input-error :messages="$errors->get('last_name')"/>
    </div>
    <div class="min-w-60 max-w-72">
        <x-input-label for="company" :value="__('Company')"/>
        <x-text-input id="company" name="company" :value="old('company', $person->company ?? '' )" class="block w-full mt-1"/>
        <x-input-error :messages="$errors->get('company')"/>
    </div>
    <div class="min-w-60 max-w-72">
        <x-input-label for="comment" :value="__('Comment')"/>
        <x-textarea cols="30" id="comment" name="comment" class="block w-full mt-1">{{ old('comment', $person->comment ?? '' ) }}</x-textarea>
        <x-input-error :messages="$errors->get('comment')"/>
    </div>
</div>
