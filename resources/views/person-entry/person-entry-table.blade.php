<table class="border-separate border-spacing-y-2 w-full text-xs sm:text-sm md:text-base">
    <thead class="[&_th:first-child]:rounded-l-lg [&_th:last-child]:rounded-r-lg">
    <tr class="*:cursor-pointer *:transition-colors">
        @foreach($columns as $col)
            <th class="p-2 text-white bg-blue-600 hover:bg-gradient-to-r from-blue-600 via-emerald-600 to-blue-600 dark:bg-violet-600 dark:from-violet-600 dark:via-pink-600 dark:to-violet-600 {{ isset($rows[0]->exit_time) ? 'w-[20%]' : 'w-[25%]' }} min-w-fit">
                {{ strtoupper(__($col)). ($col != 'Actions' && $col != 'Comment' ? ' ↓' : '') }}
            </th>
        @endforeach
    </tr>
    </thead>
    <tbody class="[&_td:first-child]:rounded-l-lg [&_td:last-child]:rounded-r-lg">
    @foreach($rows as $personEntry)
        <tr class="{{ isset($personEntry->exit_time) ?: 'ring-1 '. ($personEntry->entry_time != null ? 'ring-emerald-600':'ring-rose-600') }} bg-white dark:bg-gray-800 transition-colors duration-500 shadow rounded-lg *:text-center">
            @isset($personEntry->person->document_number)
                <td class="p-2">
                    {{ $personEntry->person->document_number }}
                </td>
            @endisset
            <td class="p-2">
                {{ $personEntry->person->name.' '.$personEntry->person->last_name }}
            </td>
            <td class="p-2">
                {{ $personEntry->person->company }}
            </td>
            <td class="p-2">
                {{ $personEntry->internalPerson->person->name.' '.$personEntry->internalPerson->person->last_name }}
            </td>
            <td class="p-2">
                @isset($personEntry->exit_time)
                    {{ \Carbon\Carbon::parse($personEntry->exit_time)->toDateString() }}
                @else
                    {{ $personEntry->comment->content }}
                @endisset
            </td>
            <td class="p-2 text-center">
                @if(isset($personEntry->exit_time))
                    <div class="flex gap-1">
                        <form action="{{ route('person-entries.create') }}" method="GET">
                            <input type="hidden" name="entry_id" value="{{ $personEntry->id }}">
                            <input type="submit" class="text-white bg-green-600 text-xl font-serif font-bold px-3 py-1 rounded cursor-pointer" value="V"/>
                        </form>
                        <a href="{{ route('person-entries.index', $personEntry->id) }}" class="text-white bg-blue-600 text-xl font-serif font-bold px-3 py-1 rounded">
                            i </a>
                    </div>
                @else
                    <form action="" method="POST">
                        <div class="flex flex-row gap-2 *:cursor-pointer items-center">
                            @if($personEntry->entry_time == null)
                                @include('person-entry.entry-button')
                            @endif
                            @include('person-entry.exit-button')
                        </div>
                    </form>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@isset($personEntry->exit_time)
    <div>
        {{ $rows->links() }}
    </div>
@endisset