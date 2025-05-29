@php use App\Models\Person;use App\Models\PersonEntry;use Illuminate\Pagination\LengthAwarePaginator;use Illuminate\Pagination\Paginator; @endphp
<div class="w-full">
    <x-header :content="__('Person List')">
        @can('create', Person::class)
            <x-primary-button wire:click="openModal('createPerson')" class="h-max">
                {{ __('New Person') }}
                <x-svg.person-icon class="w-6 h-6 ml-2 stroke-white"/>
            </x-primary-button>
        @endcan
    </x-header>
    <div class="flex flex-row justify-between px-8 py-2">
        <div>
            <label for="search" class="text-blue-600 dark:text-pink-500 font-bold">{{ __('Filter') }}:</label>
            <x-text-input type="search" id="search" name="search" class="p-1" wire:model.live.debounce.300ms="search"/>
        </div>
        <x-session-status flash="person-status"/>
    </div>
    <table class="border-separate border-spacing-y-2 text-xs sm:text-sm md:text-base w-full">
        <thead class="[&_th:first-child]:rounded-l-lg [&_th:last-child]:rounded-r-lg">
        <tr class="*:cursor-pointer *:transition-colors">
            @foreach($columns as $col)
                <th wire:click="sortBy('{{ $col }}')" class="p-2 uppercase select-none text-white bg-blue-600 hover:bg-gradient-to-r from-blue-600 via-emerald-600 to-blue-600 dark:bg-violet-700 dark:from-violet-700 dark:via-pink-600 dark:to-violet-700 min-w-fit w-[10%]">
                    {{ __($col) }}
                    @if($sortColumn == $columnMap[$col])
                        {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                    @endif
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody class="[&_td:first-child]:rounded-l-lg [&_td:last-child]:rounded-r-lg *:transition-colors">
        @foreach($rows as $personData)
            <tr class="shadow-md bg-white dark:bg-gray-700 rounded-lg text-center *:p-2">
                @foreach(array_slice($personData->toArray(), 1) as $field)
                    <td>{{ $field }}</td>
                @endforeach
                <td>
                    <div class="flex flex-row flex-wrap gap-2 justify-center">
                        @can('create', PersonEntry::class)
                            <x-svg.entry-button wire:click="openModal('createEntry', {{ $personData->id }})"/>
                        @endcan
                        @can('update', $personData)
                            <x-svg.edit-button wire:click="openModal('editPerson', {{ $personData->id }})"/>
                        @endcan
                        <a href="{{ route('person.show', ['id' => $personData->id]) }}" class="text-white bg-blue-600 text-xl font-serif font-bold px-3 py-[2px] rounded-lg border-2 border-white dark:border-gray-700 hover:ring-4 hover:ring-blue-600 max-h-max transition">
                            i </a>
                        @can('delete', $personData)
                            <x-svg.recycle-bin wire:click="openModal('deletePerson',{{ $personData->id }})" class="w-9 h-9 stroke-red-600 dark:stroke-red-200 bg-red-300 dark:bg-red-800 bg-opacity-40"/>
                        @endcan
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @if($rows instanceof Paginator || $rows instanceof LengthAwarePaginator)
        <div class="pt-4 mx-8 [&_*]:text-blue-600 dark:[&_*]:text-pink-500">
            {{ $rows->links() }}
        </div>
    @endif
    @includeWhen($activeModal == 'createEntry', 'person-entry.create')
    @includeWhen($activeModal == 'createPerson', 'person.create')
    @includeWhen($activeModal == 'editPerson', 'person.edit')
    @includeWhen($activeModal == 'deletePerson', 'person.confirm-delete')
</div>