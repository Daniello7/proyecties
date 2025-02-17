<div class="w-full">
    <div class="text-center py-2">
        <label for="search">{{ __('Filter') }}:</label>
        <x-text-input type="search" id="search" name="search" class="p-1" wire:model.live.debounce.300ms="search"/>
    </div>
    <table class="border-separate border-spacing-y-2 text-xs sm:text-sm md:text-base w-full">
        <thead class="[&_th:first-child]:rounded-l-lg [&_th:last-child]:rounded-r-lg">
        <tr class="*:cursor-pointer *:transition-colors">
            @foreach($columns as $col)
                <th wire:click="sortBy('{{ $col }}')" class="p-2 uppercase text-white bg-blue-600 hover:bg-gradient-to-r from-blue-600 via-emerald-600 to-blue-600 dark:bg-violet-600 dark:from-violet-600 dark:via-pink-600 dark:to-violet-600 min-w-fit w-[10%]">
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
            <tr class="{{ isset($personEntry->exit_time) ?: 'ring-1 '. ($personEntry->entry_time != null ? 'ring-emerald-600':'ring-rose-600') }} shadow-md bg-white dark:bg-gray-700 rounded-lg text-center *:p-2">
                @if($info === 'latest_entries')
                    <td>{{ $personEntry->person->document_number }}</td>
                @endif
                @isset($personEntry->person)
                    <td>{{ $personEntry->person->name.' '.$personEntry->person->last_name }}</td>
                    <td>{{ $personEntry->person->company }}</td>
                @endisset
                <td>{{ $personEntry->internalPerson->person->name.' '.$personEntry->internalPerson->person->last_name }}</td>
                @if($info === 'show')
                    <td>{{ __($personEntry->reason) }}</td>
                    <td>{{ $personEntry->user->name }}</td>
                    <td>{{ $personEntry->arrival_time }}</td>
                    <td>{{ $personEntry->entry_time }}</td>
                @endif
                @isset($personEntry->exit_time)
                    <td>{{ $personEntry->exit_time }}</td>
                @endisset
                @isset($personEntry->comment)
                    <td>{{ $personEntry->comment->content }}</td>
                @endisset

                <!-- Actions -->
                <td>
                    <div class="flex flex-row flex-wrap gap-2 justify-center">
                        @if(!$info)
                            @if($personEntry->entry_time == null)
                                <x-entry-button wire:click="updateEntry({{ $personEntry->id }})"/>
                            @endif
                            <x-exit-button wire:click="updateExit({{ $personEntry->id }})"/>
                        @endif
                        @if($info === 'latest_entries')
                            <form action="{{ route('person-entries.create') }}" method="GET">
                                <input type="hidden" name="person_id" value="{{ $personEntry->person_id }}">
                                <input type="submit" class="text-white bg-green-600 text-xl font-serif font-bold px-3 py-1 rounded cursor-pointer" value="V"/>
                            </form>
                        @endif
                        @if($info != 'show')
                            <a href="{{ route('person.show', ['id' => $personEntry->person_id]) }}" class="text-white bg-blue-600 text-xl font-serif font-bold px-4 py-1 rounded">
                                i </a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @if($rows instanceof \Illuminate\Pagination\Paginator)
        {{ $rows->links() }}
    @endif
</div>
