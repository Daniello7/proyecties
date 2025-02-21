<select {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm pr-8']) }}>
    <option value="0">{{ count($keys) > 0 ? __('Select a key') : __('Not selected Zone') }}</option>
    @foreach($keys as $key)
        <option value="{{ $key->id }}">
            {{ $key->name }}
        </option>
    @endforeach
</select>