<div class="w-full">
    <x-header content='PDF'></x-header>
    <div class="flex flex-row justify-between px-8 py-2">
        <div>
            <label for="search" class="text-blue-600 dark:text-pink-500 font-bold">{{ __('Search') }}:</label>
            <x-text-input type="search" :placeholder="__('Search').' . . .'" class="p-1" wire:model.live.debounce.300ms="search"/>
        </div>
        <x-session-status flash="success" class="p-1"/>
    </div>
    <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
    <div>
        @foreach($pdfsExport as $pdf)
            <div>
                <a href="{{ Storage::url($pdf->file_path) }}" target="_blank">{{ $pdf->type.' - '.$pdf->created_at }}</a>
            </div>
        @endforeach
    </div>
    <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
</div>
