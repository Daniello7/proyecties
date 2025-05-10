<div class="w-full">
    <div class="flex flex-col justify-between p-2">
        <div class="flex flex-row justify-between px-8">
            <div>
                <label for="search_package" class="text-blue-600 dark:text-pink-500 font-bold">{{ __('Search').':' }}</label>
                <x-text-input type="search" class="p-1" :placeholder="__('Search').' . . .'" wire:model.live.debounce.300ms="search"/>
            </div>
            <div class="flex flex-row gap-2">
                <x-session-status flash="package-status" class="py-1"/>
                <x-svg.recycle-bin class="w-9 h-9 stroke-white dark:hover:ring-red-700 bg-red-600 dark:bg-red-700"/>
            </div>
        </div>
    </div>
    <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
    @if(count($rows) < 1)
        <div class="custom-gradient-text text-2xl py-5 text-center w-full">
            {{ __('Recycle bin is empty') }}
        </div>
    @else
        <div class="max-h-[600px] overflow-hidden overflow-y-scroll scrollbar-custom px-4">
            <table class="border-separate border-spacing-y-2 text-xs sm:text-sm w-full">
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
                @foreach($rows as $package)
                    <tr class="shadow-md bg-white dark:bg-gray-700 rounded-lg text-center *:py-2 *:px-1">
                        <!-- Fields -->
                        <td>{{ __(ucfirst($package->type)) }}</td>
                        <td>{{ $package->agency }}</td>
                        <td>{{ $package->external_entity }}</td>
                        <td>{{ $package->internalPerson->person->name. ' ' .$package->internalPerson->person->last_name }}</td>
                        <td>{{ $package->entry_time }}</td>
                        <td>{{ $package->receiver->name }}</td>
                        <td>{{ $package->exit_time }}</td>
                        <td>{{ $package->deliver?->name }}</td>
                        <td>{{ $package->package_count }}</td>
                        <td>{{ $package->retired_by }}</td>
                        <td>{{ $package->comment }}</td>
                        <!-- Actions -->
                        <td>
                            <div class="flex flex-row flex-wrap gap-2 justify-center">
                                <x-svg.restore-button wire:click="restore({{ $package->id }})"/>
                                <x-svg.recycle-bin wire:click="forceDelete({{ $package->id }})" class="w-7 h-7 stroke-red-600 dark:stroke-red-200 bg-red-300 dark:bg-red-800 bg-opacity-40"/>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
    <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
    <div class="pt-4 mx-8 [&_*]:text-blue-600 dark:[&_*]:text-pink-500">
        {{ $rows->links() }}
    </div>
</div>