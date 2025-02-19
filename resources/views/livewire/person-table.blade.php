<div class="w-full">
    <div class="flex flex-row justify-between px-8 py-2">
        <div>
            <label for="search" class="text-blue-600 dark:text-pink-500 font-bold">{{ __('Filter') }}:</label>
            <x-text-input type="search" id="search" name="search" class="p-1" wire:model.live.debounce.300ms="search"/>
        </div>
    </div>
    <table class="border-separate border-spacing-y-2 text-xs sm:text-sm md:text-base w-full">
        <thead class="[&_th:first-child]:rounded-l-lg [&_th:last-child]:rounded-r-lg">
        <tr class="*:cursor-pointer *:transition-colors">
            @foreach($columns as $col)
                <th wire:click="sortBy('{{ $col }}')" class="p-2 uppercase select-none text-white bg-blue-600 hover:bg-gradient-to-r from-blue-600 via-emerald-600 to-blue-600 dark:bg-violet-700 dark:from-violet-700 dark:via-pink-600 dark:to-violet-700 min-w-fit w-[10%]">
                    {{ __($col) }}
                    @if($sortColumn == $columnMap[$col])
                        {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                    @endif
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody class="[&_td:first-child]:rounded-l-lg [&_td:last-child]:rounded-r-lg *:transition-colors">
        @foreach($rows as $person)
            <tr class="shadow-md bg-white dark:bg-gray-700 rounded-lg text-center *:p-2">
                @foreach(array_slice($person->toArray(), 1) as $field)
                    <td>{{ $field }}</td>
                @endforeach
                <td>
                    <form action="{{ route('person-entries.create') }}" method="GET">
                        <input type="hidden" name="person_id" id="person_id" value="{{ $person->id }}">
                        <x-svg.confirm-button type="submit"/>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>