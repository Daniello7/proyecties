<table class="border-separate border-spacing-y-2 w-full">
    <thead class="[&_th:first-child]:rounded-l-lg [&_th:last-child]:rounded-r-lg">
    <tr class="*:cursor-pointer *:transition-colors hover:*:ring-0">
        @foreach($columns as $col)
            <th class="p-2 text-white bg-emerald-600 ring-1 ring-emerald-600 hover:bg-emerald-500 {{ count($columns) > 7 ? 'w-[14%]' : 'w-[20%]' }} min-w-fit">
                {{ __($col). ($col != 'Actions' && $col != 'Comment' && $col != 'Reason' ? ' ↓' : '') }}
            </th>
        @endforeach
    </tr>
    </thead>
    <tbody class="[&_td:first-child]:rounded-l-lg [&_td:last-child]:rounded-r-lg">
    @foreach($rows as $personEntry)
        <tr class="{{ isset($personEntry->user) ?: 'ring-2 '. ($personEntry->entry_time != null ? 'ring-emerald-600':'ring-rose-600') }} bg-white dark:bg-gray-800 transition-colors shadow rounded-lg *:text-center">
            @isset($personEntry->user)
                <td class="p-2">
                    {{ $personEntry->user->name }}
                </td>
                <td class="p-2">
                    {{ $personEntry->arrival_time }}
                </td>
                <td class="p-2">
                    {{ $personEntry->entry_time }}
                </td>
                <td class="p-2">
                    {{ $personEntry->exit_time }}
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
            <td class="p-2 text-center">
                {{ __($personEntry->reason) }}
            </td>
            <td class="p-2">
                {{ $personEntry->comment->content }}
            </td>
            <td class="p-2 text-center">
                @if(isset($personEntry->user))
                    <div class="flex gap-1">
                        <form action="{{ route('person-entries.create') }}" method="GET">
                            <input type="hidden" name="entry_id" value="{{ $personEntry->id }}">
                            <input type="submit" class="text-white bg-green-600 text-xl font-serif font-bold px-3 py-1 rounded cursor-pointer" value="V"/>
                        </form>
                        <a href="{{ route('person-entries', $personEntry->id) }}" class="text-white bg-blue-600 text-xl font-serif font-bold px-3 py-1 rounded">
                            i </a>
                    </div>
                @else
                    <form action="" method="POST">
                        <div class="flex flex-col gap-2 *:cursor-pointer items-center">
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
@isset($personEntry->user)
    <div>
        {{ $rows->links() }}
    </div>
@endisset