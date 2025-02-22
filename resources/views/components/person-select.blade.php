<select {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm pr-8']) }}>
    <option>{{ $slot }}</option>
    @foreach($people as $person)
        <option value="{{ $includeExternal ? $person->id : $person->internalPerson->id }}" {{ $oldContact == $person->id ? 'selected' : ''}}>
            {{ $person->name.' '.$person->last_name }}
        </option>
    @endforeach
</select>