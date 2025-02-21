<select {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm pr-8']) }}>
    <option value="">{{ __('Select a zone') }}</option>
    @foreach(\App\Models\Key::ZONES as $zone)
        <option value="{{ $zone }}">
            {{(array_search($zone,\App\Models\Key::ZONES)+1).'. '. __($zone) }}
        </option>
    @endforeach
</select>