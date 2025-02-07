<table class="border-separate border-spacing-y-2 w-full">
    <thead>
    <tr class="*:cursor-pointer *:px-4 *:py-2 *:transition-colors hover:*:ring-0">
        @foreach($columns as $col)
            <th class="text-white bg-emerald-600 ring-2 ring-emerald-600 hover:bg-emerald-500 rounded {{ count($columns) > 7 ? 'w-[16%]' : 'w-[20%]' }} max-w-max">
                {{ __($col). ($col != 'Actions' ? ' ↓' : '') }}
            </th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($rows as $row)
        <tr class="ring-2 {{ $row->entry_time != null ? 'ring-emerald-600':'ring-rose-600' }} bg-white dark:bg-gray-800 transition-colors *:shadow rounded-lg">
            <td class="p-2 rounded-l-lg">
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
            <td class="p-2 rounded-r-lg">
                <form action="" method="POST">
                    <div class="flex flex-col gap-2 *:cursor-pointer items-center">
                        @if($row->entry_time == null)
                            <x-entry-button/>
                        @endif
                        <x-exit-button/>
                    </div>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
