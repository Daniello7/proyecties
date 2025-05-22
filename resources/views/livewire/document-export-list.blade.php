<div class="w-full flex flex-col gap-8 p-8">
    <section class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg transition-colors z-10">
        <x-header :content="__('Options')"></x-header>
        <div class="py-6 flex flex-row gap-4 justify-evenly">
            <x-button-box wire:click="changeType('pdf')">
                <x-svg.pdf-icon class="w-8 stroke-blue-600"/>
                PDF
            </x-button-box>
            <x-button-box wire:click="changeType('xlsx')">
                <x-svg.excel-icon class="w-8 stroke-blue-600 fill-blue-600"/>
                Excel
            </x-button-box>
        </div>
    </section>
    <section class="text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-lg">
        <x-header :content="__('Documents').' '. ($type == 'pdf' ? 'PDF' : 'EXCEL') ">
            <div class="flex items-center px-4 gap-2">
                <button wire:click="viewedAll()" class="rounded-full overflow-hidden border-4 border-transparent hover:border-blue-600 dark:hover:border-blue-200">
                    <x-svg.eye-icon class="w-8 text-blue-600 bg-blue-100 dark:bg-blue-800 dark:text-blue-200 p-1"/>
                </button>
                <button wire:click="notViewedAll()" class="rounded-full overflow-hidden border-4 border-transparent hover:border-blue-600 dark:hover:border-blue-200">
                    <x-svg.eye-off-icon class="w-8 text-gray-500 bg-gray-200 dark:bg-gray-700 dark:text-gray-200 p-1"/>
                </button>
            </div>
        </x-header>
        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-2">
            @if(count($documentExports) < 1)
                <div class="custom-gradient-text text-2xl py-5 text-center w-full">
                    {{ __('There are no results') }}
                </div>
            @else
                @foreach($documentExports as $document)
                    <!-- Documents -->
                    <div class="shadow-md rounded-md flex justify-between items-center px-4 group">
                        <button wire:click="updateViewedAt({{ $document->id }})" class="p-4 *:hover:custom-gradient-text">
                            <div class="{{ $document->viewed_at != null ?: 'font-extrabold' }} flex gap-2">
                                @if($type == 'pdf')
                                    <x-svg.pdf-icon class="w-6 h-6 stroke-red-600"/>
                                @else
                                    <x-svg.excel-icon class="w-6 h-6 stroke-green-600 fill-green-600"/>
                                @endif
                                {{ __($document->filename).' - '.
                                \Carbon\Carbon::parse($document->created_at)
                                ->locale('es')
                                ->isoFormat('D [de] MMMM [de] YYYY H:mm') }}
                            </div>
                        </button>
                        <!-- Options -->
                        <section class="flex gap-2 items-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <x-svg.recycle-bin wire:click="deletePdf({{ $document->id }})" class="w-8 h-8 stroke-red-600 dark:stroke-red-300 bg-red-300 dark:bg-red-900 bg-opacity-40"/>
                            <button wire:click="changeViewedAt({{ $document->id }})" class="rounded-full overflow-hidden border-4 border-transparent hover:border-blue-600 dark:hover:border-blue-200">
                                @if($document->viewed_at)
                                    <x-svg.eye-off-icon class="w-8 text-gray-500 bg-gray-200 dark:bg-gray-700 dark:text-gray-200 p-1"/>
                                @else
                                    <x-svg.eye-icon class="w-8 text-blue-600 bg-blue-100 dark:bg-blue-800 dark:text-blue-200 p-1"/>
                                @endif
                            </button>
                        </section>
                    </div>
                @endforeach
            @endif
        </div>
        <hr class="mx-2 border-blue-600 dark:border-pink-600 opacity-50">
    </section>
</div>
<script>
    window.addEventListener('open-document', event => {
        let a = document.createElement('a')
        a.href = event.detail.documentUrl
        a.target = '_blank'
        a.rel = 'noopener noreferrer'
        document.body.append(a)
        a.click()
        a.remove()
    })
</script>