<div class="w-full">
    <div class="flex flex-col justify-between p-2">
        <div class="flex flex-row justify-between">
            <x-text-input type="search" class="p-1 w-28" :placeholder="__('Search').' . . .'" wire:model.live.debounce.300ms="search"/>
            <x-session-status flash="key-status" class="py-1"/>
        </div>
    </div>
    <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
    <table class="border-separate border-spacing-y-2 text-xs sm:text-sm md:text-base w-full">
        <thead class="[&_th:first-child]:rounded-l-lg [&_th:last-child]:rounded-r-lg">
        <tr class="*:cursor-pointer *:transition-colors">
            @foreach($columns as $col)
                <th wire:click="sortBy('{{ $columnMap[$col] }}')" class="py-2 px-1 uppercase select-none text-white bg-blue-600 hover:bg-gradient-to-r from-blue-600 via-emerald-600 to-blue-600 dark:bg-violet-700 dark:from-violet-700 dark:via-pink-600 dark:to-violet-700 min-w-fit w-[10%]">
                    {{ __($col) }}
                    @if($sortColumn == $columnMap[$col])
                        {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                    @endif
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody class="[&_td:first-child]:rounded-l-lg [&_td:last-child]:rounded-r-lg *:transition-colors">
        @foreach($rows as $keyControl)
            <tr class="shadow-md bg-white dark:bg-gray-700 rounded-lg text-center *:py-2 *:px-1">
                <!-- Fields -->
                <td>{{ $keyControl->person->name. ' '.$keyControl->person->last_name }}</td>
                <td>{{ "{$keyControl->key->area_id}.{$keyControl->key->area_key_number} - {$keyControl->key->name}" }}</td>
                <td>{{ $keyControl->deliver->name }}</td>
                <td>{{ substr($keyControl->exit_time,0,-3) }}</td>
                <td>{{ $keyControl->receiver->name }}</td>
                <td>{{ substr($keyControl->entry_time,0,-3) }}</td>
                <td>{{ $keyControl->comment }}</td>
                <!-- Actions -->
                <td>
                    <div class="flex flex-row flex-wrap gap-2 justify-center">
                        @can('update', $keyControl)
                            <x-svg.edit-button wire:click="openModal('editKeyControl',{{ $keyControl->id }})"/>
                        @endcan
                        @can('delete', $keyControl)
                            <x-svg.recycle-bin wire:click="openModal('deleteKeyControl',{{ $keyControl->id }})" class="w-9 h-9 stroke-red-600 dark:stroke-red-300 bg-red-300 dark:bg-red-900 bg-opacity-40"/>
                        @endcan
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
    <div class="pt-4 mx-8 [&_*]:text-blue-600 dark:[&_*]:text-pink-500">
        {{ $rows->links() }}
    </div>
    @includeWhen($activeModal == 'editKeyControl', 'key-control.edit')
    @includeWhen($activeModal == 'deleteKeyControl', 'key-control.confirm-delete')
</div>