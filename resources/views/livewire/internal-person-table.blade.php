<div class="w-full">
    <div class="flex flex-row justify-between items-center px-8 py-2">
        <div>
            <label for="search" class="text-blue-600 dark:text-pink-500 font-bold">{{ __('Filter') }}:</label>
            <x-text-input type="search" id="search" name="search" class="p-1" wire:model.live.debounce.300ms="search"/>
        </div>
        <x-session-status flash="internal-person-status"/>
    </div>
    <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
    <table class="border-separate border-spacing-y-2 text-xs sm:text-sm md:text-base w-full">
        <thead class="[&_th:first-child]:rounded-l-lg [&_th:last-child]:rounded-r-lg">
        <tr class="*:cursor-pointer *:transition-colors">
            @foreach($columns as $col)
                <th wire:click="sortBy('{{ $columnMap[$col] }}')" class="p-2 uppercase select-none text-white bg-blue-600 hover:bg-gradient-to-r from-blue-600 via-emerald-600 to-blue-600 dark:bg-violet-700 dark:from-violet-700 dark:via-pink-600 dark:to-violet-700 min-w-fit w-[10%]">
                    {{ __($col) }}
                    @if($sortColumn == $columnMap[$col])
                        {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                    @endif
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody class="[&_td:first-child]:rounded-l-lg [&_td:last-child]:rounded-r-lg *:transition-colors">
        @foreach($internalPeople as $internalPerson)
            <tr class="shadow-md bg-white dark:bg-gray-700 rounded-lg text-center *:p-2">
                @foreach($internalPerson->toArray() as $field)
                    <td>{{ $field }}</td>
                @endforeach

                <!-- Actions -->
                <td class="flex flex-row flex-wrap gap-2 justify-center">
                    @role('porter')
                    <x-svg.exit-button/>
                    @endrole
                    @if(auth()->user()->hasAnyRole('admin', 'rrhh'))
                        <x-svg.edit-button wire:click="openModal('editInternalPerson', {{ $internalPerson->id }})"/>
                        <a href="{{ route('internal-person.show', ['id' => $internalPerson->id]) }}" class="text-white bg-blue-600 text-xl font-serif font-bold px-3 py-[2px] rounded-lg border-2 border-white dark:border-gray-700 hover:ring-4 hover:ring-blue-600 max-h-max transition">
                            i </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @includeWhen($activeModal == 'editInternalPerson', 'internal-person.edit')
</div>