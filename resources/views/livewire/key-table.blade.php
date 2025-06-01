@php use App\Models\Key; @endphp
<div class="w-full">
    <x-header :content="__('Key Control').' - '.__('Keys')">
        @can('create',Key::class)
            <x-primary-button wire:click="openModal('createKey')" class="h-max m-2">
                {{ __('New Key') }}
                <x-svg.add-key-icon class="w-6 h-6 ml-2 stroke-white"/>
            </x-primary-button>
        @endcan
    </x-header>
    <div class="flex flex-col justify-between p-2">
        <div class="flex flex-row justify-between">
            <div>
                <label for="search" class="text-blue-600 dark:text-pink-500 font-bold">{{ __('Search') }}:</label>
                <x-text-input type="search" id="search" class="p-1" :placeholder="'ID '.__('Area').'/'.__('Key').' . . .'" wire:model.live.debounce.300ms="search"/>
            </div>
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
        @foreach($rows as $keyData)
            <tr class="shadow-md bg-white dark:bg-gray-700 rounded-lg text-center *:py-2 *:px-1">
                <!-- Fields -->
                <td>{{ $keyData->area->id.' - '. $keyData->area->name }}</td>
                <td>{{ "{$keyData->area->id}.$keyData->area_key_number - $keyData->name" }}</td>
                <!-- Actions -->
                <td>
                    <div class="flex flex-row flex-wrap gap-2 justify-center">
                        @can('update', $keyData)
                            <x-svg.edit-button wire:click="openModal('editKey',{{ $keyData->id }})"/>
                        @endcan
                        @can('delete', $keyData)
                            <x-svg.recycle-bin wire:click="openModal('deleteKey',{{ $keyData->id }})" class="w-9 h-9 stroke-red-600 dark:stroke-red-300 bg-red-300 dark:bg-red-900 bg-opacity-40"/>
                        @endcan
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
    @includeWhen($activeModal == 'createKey', 'keys.create')
    @includeWhen($activeModal == 'editKey', 'keys.edit')
    @includeWhen($activeModal == 'deleteKey', 'keys.confirm-delete')
</div>