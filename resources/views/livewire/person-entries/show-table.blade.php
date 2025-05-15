<div class="w-full">
    <div class="flex flex-row justify-between px-8 py-2">
        <div>
            <label for="search" class="text-blue-600 dark:text-pink-500 font-bold">{{ __('Search') }}:</label>
            <x-text-input type="search" :placeholder="__('Search').' . . .'" class="p-1" wire:model.live.debounce.300ms="search"/>
        </div>
        <x-session-status flash="success" class="p-1"/>
    </div>
    <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
    <div class="max-h-[600px] overflow-hidden overflow-y-scroll scrollbar-custom px-4">
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
                    <td>{{ $personEntry->internalPerson->person->name.' '.$personEntry->internalPerson->person->last_name }}</td>
                    <td>{{ __($personEntry->reason) }}</td>
                    <td>{{ $personEntry->user->name }}</td>
                    <td>{{ $personEntry->arrival_time }}</td>
                    <td>{{ $personEntry->entry_time }}</td>
                    <td>{{ $personEntry->exit_time }}</td>
                    <td>{{ $personEntry->comment }}</td>
                    <!-- Actions -->
                    <td>
                        <div class="flex flex-row flex-wrap gap-2 justify-center">
                            <x-svg.edit-button wire:click="openModal('editEntry', {{ $personEntry->id }})"/>
                            <x-svg.delete-button wire:click="openModal('destroyEntry', {{ $personEntry->id }})"/>
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
    @includeWhen($activeModal == 'editEntry', 'person-entry.edit')
    @includeWhen($activeModal == 'destroyEntry', 'livewire.person-entries.modals.confirm-destroy')
</div>
