<div class="w-full">
    <div class="flex flex-col justify-between p-2">
        <div class="flex flex-row justify-between">
            <div>
                <label for="search_package" class="text-blue-600 dark:text-pink-500 font-bold">{{ __('Search').':' }}</label>
                <x-text-input type="search" class="p-1" :placeholder="__('Search').' . . .'" wire:model.live.debounce.300ms="search"/>
            </div>
            <x-session-status flash="package-status" class="py-1"/>
        </div>
    </div>
    <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
    <div class="h-[600px] overflow-hidden overflow-y-scroll scrollbar-custom px-4">
        <table class="border-separate border-spacing-y-2 text-xs sm:text-sm w-full">
            <thead class="[&_th:first-child]:rounded-l-lg [&_th:last-child]:rounded-r-lg">
            <tr class="*:cursor-pointer *:transition-colors">
                @foreach($columns as $col)
                    <th wire:click="sortBy('{{ $col }}')" class="py-2 px-1 uppercase select-none text-white bg-blue-600 hover:bg-gradient-to-r from-blue-600 via-emerald-600 to-blue-600 dark:bg-violet-700 dark:from-violet-700 dark:via-pink-600 dark:to-violet-700 min-w-fit w-[10%]">
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
                    @if(!$isHomeView)
                        <td>{{ $package->receiver->name }}</td>
                        <td>{{ $package->exit_time }}</td>
                        <td>{{ $package->deliver->name }}</td>
                        <td>{{ $package->package_count }}</td>
                        <td>{{ $package->retired_by }}</td>
                    @endif
                    <td>{{ $package->comment }}</td>
                    <!-- Actions -->
                    <td>
                        <div class="flex flex-row flex-wrap gap-2 justify-center">
                            @if($isHomeView)
                                <x-svg.arrow-down-button wire:click="updatePackageRecord({{ $package->id }})"/>
                                <x-svg.delete-button wire:click="deletePackageRecord({{ $package->id }})"/>
                            @else
                                <x-svg.edit-button href="{{ route('packages.edit', $package->id) }}"/>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
    @if(!$isHomeView)
        <div class="pt-4 mx-8 [&_*]:text-blue-600 dark:[&_*]:text-pink-500">
            {{ $rows->links() }}
        </div>
    @endif
</div>