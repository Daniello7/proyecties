<div class="w-full">
    <div class="flex flex-row justify-between px-8 py-2">
        <div>
            <label for="search" class="text-blue-600 dark:text-pink-500 font-bold">{{ __('Search') }}:</label>
            <x-text-input type="search" :placeholder="__('Search').' . . .'" class="p-1" wire:model.live.debounce.300ms="search"/>
        </div>
        <x-session-status flash="success" class="p-1"/>
    </div>
    <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
    <div class="h-[600px] overflow-hidden overflow-y-scroll scrollbar-custom px-4">
        <table class="border-separate border-spacing-y-2 text-xs sm:text-sm md:text-base w-full">
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
                <tr class="{{ isset($personEntry->exit_time) ?: 'ring-1 '. ($personEntry->entry_time != null ? 'ring-emerald-600':'ring-rose-600') }} shadow-md bg-white dark:bg-gray-700 rounded-lg text-center *:py-2 *:px-1">
                    <!-- Fields -->
                    <td>{{ $personEntry->person->document_number }}</td>
                    @isset($personEntry->person)
                        <td>{{ $personEntry->person->name.' '.$personEntry->person->last_name }}</td>
                        <td>{{ $personEntry->person->company }}</td>
                    @endisset
                    <td>{{ $personEntry->internalPerson->person->name.' '.$personEntry->internalPerson->person->last_name }}</td>
                    @isset($personEntry->exit_time)
                        <td>{{ $personEntry->exit_time }}</td>
                    @endisset

                    <!-- Actions -->
                    <td>
                        <div class="flex flex-row flex-wrap gap-2 justify-center">
                            @can('update',\App\Models\PersonEntry::class)
                                <x-svg.edit-button href="{{ route('person-entries.edit', $personEntry->id) }}"/>
                            @endcan
                            <form action="{{ route('person-entries.create') }}" method="GET">
                                <input type="hidden" name="person_id" value="{{ $personEntry->person_id }}">
                                <x-svg.entry-button type="submit"/>
                            </form>
                            <a href="{{ route('person.show', ['id' => $personEntry->person_id]) }}" class="text-white bg-blue-600 text-xl font-serif font-bold px-3 py-[2px] rounded-lg border-2 border-white dark:border-gray-700 hover:ring-4 hover:ring-blue-600 max-h-max transition">
                                i </a>
                            @can('delete',\App\Models\PersonEntry::class)
                                <x-svg.delete-button wire:click="destroyPersonEntry({{ $personEntry->id }})"/>
                            @endcan
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
    @if($rows instanceof \Illuminate\Pagination\Paginator || $rows instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="pt-4 mx-8 [&_*]:text-blue-600 dark:[&_*]:text-pink-500">
            {{ $rows->links() }}
        </div>
    @endif
</div>
