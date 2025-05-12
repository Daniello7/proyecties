<div class="w-full">
    <x-header content='PDF'></x-header>
    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-2">
        @if(count($pdfsExport) < 1)
            <div class="custom-gradient-text text-2xl py-5 text-center w-full">
                {{ __('There are no results') }}
            </div>
        @else
            @foreach($pdfsExport as $pdf)
                <div class="shadow-md rounded-md">
                    <button wire:click="updateViewedAt({{ $pdf->id }})" class="p-4 *:hover:custom-gradient-text">
                        <div class="{{ $pdf->viewed_at != null ?: 'font-extrabold' }} flex gap-2">
                            <x-svg.pdf-icon class="w-6 h-6 stroke-red-600"/>
                            {{ __($pdf->type).' - '.
                            \Carbon\Carbon::parse($pdf->created_at)
                            ->locale('es')
                            ->isoFormat('D [de] MMMM [de] YYYY H:mm') }}
                        </div>
                    </button>
                    <x-svg.recycle-bin wire:click="deletePdf({{ $pdf->id }})" class="w-8 h-8 stroke-red-600 dark:stroke-red-300 bg-red-300 dark:bg-red-900 bg-opacity-40"/>
                </div>
            @endforeach
        @endif
    </div>
    <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
</div>
<script>
    window.addEventListener('open-pdf', event => {
        let a = document.createElement('a')
        a.href = event.detail.pdfUrl
        a.target = '_blank'
        a.rel = 'noopener noreferrer'
        document.body.append(a)
        a.click()
        a.remove()
    })
</script>