<div>
    <div class="flex gap-4 p-4 items-center">
        <x-primary-button id="dropdownButton" wire:click="updateShowDocuments">
            {{ __('Commitments issued') }}
            <x-svg.dropdown-icon id="dropdownIcon" class="fill-white h-6 w-6 transition {{ $showDocuments ? 'rotate-180':'' }}"/>
        </x-primary-button>
        <x-session-status flash="document-status"/>
    </div>
    <section id="dropdownContent" class="{{ $showDocuments ? '' : 'hidden' }} flex gap-4 w-full">
        <ul class="p-6">
            @foreach ($person->documents as $pdf)
                <li class="flex gap-4">
                    <a href="{{ Storage::url($pdf->path) }}" target="_blank" class="text-blue-500 flex my-2 gap-2">
                        <x-svg.pdf-icon class="h-6 w-6 stroke-red-600"/>
                        {{ "Rules_{$pdf->created_at}.pdf" }}
                    </a>
                    <x-svg.recycle-bin class="w-8 h-8 stroke-red-600 bg-red-300 bg-opacity-20" wire:click="deleteDocument({{$pdf->id}})"/>
                </li>
            @endforeach
        </ul>
        <livewire:drop-zone :person="$person"/>
    </section>
</div>