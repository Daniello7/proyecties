<div class="w-full">
    <div class="flex flex-row justify-between px-8 py-2">
        <div>
            <label for="search" class="text-blue-600 dark:text-pink-500 font-bold">{{ __('Search') }}:</label>
            <x-text-input type="search" :placeholder="__('Search').' . . .'" class="p-1" wire:model.live.debounce.300ms="search"/>
        </div>
        <x-session-status flash="success" class="p-1"/>
    </div>
    <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
    <table class="border-separate border-spacing-y-2 text-xs sm:text-sm w-full">
        <thead class="[&_th:first-child]:rounded-l-lg [&_th:last-child]:rounded-r-lg">
        <tr class="*:cursor-pointer *:transition-colors">
            @foreach($columns as $col)
                <th wire:click="sortBy('{{ $columnMap[$col] }}')" class="py-2 px-1 uppercase select-none text-white bg-blue-600 hover:bg-gradient-to-r from-blue-600 via-emerald-600 to-blue-600 dark:bg-violet-600 dark:from-violet-600 dark:via-pink-600 dark:to-violet-600 min-w-fit w-[10%]">
                    {{ __($col) }}
                    @if($sortColumn == $columnMap[$col])
                        {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                    @endif
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody class="[&_td:first-child]:rounded-l-lg [&_td:last-child]:rounded-r-lg *:transition-colors">
        @foreach($rows as $personEntry)
            <tr class="{{ isset($personEntry->exit_time) ?: '[&_td:first-child]:border-l-4'. ($personEntry->entry_time != null ? '[&_td:first-child]:border-emerald-600':'[&_td:first-child]:border-blue-600') }} shadow-md bg-white dark:bg-gray-700 rounded-lg text-center *:py-2 *:px-1">
                <!-- Columns -->
                <td>{{ $personEntry->person->name.' '.$personEntry->person->last_name }}</td>
                <td>{{ $personEntry->person->company }}</td>
                <td>{{ $personEntry->internalPerson->person->name.' '.$personEntry->internalPerson->person->last_name }}</td>
                <td>{{ $personEntry->comment }}</td>
                <!-- Actions -->
                <td>
                    <div class="flex flex-row flex-wrap gap-2 justify-center">
                        @if($personEntry->entry_time == null)
                            <x-svg.entry-button wire:click="updateEntry({{ $personEntry->id }})"/>
                        @endif
                        <x-svg.exit-button wire:click="updateExit({{ $personEntry->id }})"/>
                        <a href="{{ route('person.show', ['id' => $personEntry->person_id]) }}" class="text-white bg-blue-600 text-xl font-serif font-bold px-3 py-[2px] rounded-lg border-2 border-white dark:border-gray-700 hover:ring-4 hover:ring-blue-600 max-h-max transition">
                            i </a>
                        @can('cancel',\App\Models\PersonEntry::class)
                            <x-svg.delete-button wire:click="destroyPersonEntry({{ $personEntry->id }})"/>
                        @endcan
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
</div>
