<table class="table-auto border-collapse border border-gray-300 w-full">
    <thead>
    <tr class="">
        @foreach($alias as $colName)
            <th class="border px-4 py-2">
                {{ $colName }}
            </th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($rows as $row)
        <tr class="hover:bg-gray-100">
            @foreach($columns as $col)
                <td class="border border-gray-400 px-4 py-2">
                    @if(isset($row->$col) && is_object($row->$col))
                        {{ $row->$col->name }} {{-- Por ver --}}
                    @else
                        {{ $row->$col }}
                    @endif
                </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
