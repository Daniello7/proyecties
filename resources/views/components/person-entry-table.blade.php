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
    @foreach($rows as $row)
        <tr class="{{ isset($row->user) ?: 'ring-2 '. ($row->entry_time != null ? 'ring-emerald-600':'ring-rose-600') }} bg-white dark:bg-gray-800 transition-colors shadow rounded-lg *:text-center">
            @isset($row->user)
                <td class="p-2">
                    {{ $row->user->name }}
                </td>
                <td class="p-2">
                    {{ $row->arrival_time }}
                </td>
                <td class="p-2">
                    {{ $row->entry_time }}
                </td>
                <td class="p-2">
                    {{ $row->exit_time }}
                </td>
            @endisset
            <td class="p-2">
                {{ $row->person->name.' '.$row->person->last_name }}
            </td>
            <td class="p-2">
                {{ $row->person->company }}
            </td>
            <td class="p-2">
                {{ $row->internalPerson->person->name.' '.$row->internalPerson->person->last_name }}
            </td>
            <td class="p-2 text-center">
                {{ __($row->reason) }}
            </td>
            <td class="p-2">
                {{ $row->comment->content }}
            </td>
            <td class="p-2 text-center">
                @if(isset($row->user))
                    <a href="{{ route('person-entries', $row->id) }}" class="text-white bg-blue-600 text-xl font-serif font-bold px-3 py-1 rounded">
                        i </a>
                @else
                    <form action="" method="POST">
                        <div class="flex flex-col gap-2 *:cursor-pointer items-center">
                            @if($row->entry_time == null)
                                <x-entry-button/>
                            @endif
                            <x-exit-button/>
                        </div>
                    </form>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
