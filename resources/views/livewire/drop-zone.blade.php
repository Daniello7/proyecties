@props(['person'])
<section>
    <div class="drop-zone shadow-md" id="dropZone">
        <svg id="dropZoneRect" class="w-full h-full absolute inset-0 rounded-lg">
            <rect width="100%" height="100%" fill="none" rx="10" stroke-dasharray="50%"/>
        </svg>
        <div class="drop-zone-text">
            <p>{{ __($dropZoneMessage) }}</p>
        </div>
        <input type="file" id="fileInput" accept="{{ $allowedTypeFile === 'excel' ? '.xlsx,.xls' : '.pdf' }}" wire:model="dropZoneFile">
        <x-input-error :messages="$errors->get('dropZoneFile')"/>
        <div id="fileList" class="file-list">
            @if($original_name)
                <span class="file-item">
                    @if($allowedTypeFile === 'excel')
                    @else
                        <x-svg.pdf-icon class="h-6 w-6 mr-2 stroke-red-600"/>
                    @endif
                    {{ $original_name }}
            </span>
            @endif
        </div>
    </div>
    <div class="ml-8">
        <x-primary-button type="button" wire:target="upload" id="selectButton" class="upload-button">
            {{ __('Select').' '.__('File') }}...
        </x-primary-button>
        @if($showUploadButton)
            <x-primary-button wire:click="uploadFile" wire:target="uploadFile" id="uploadButton" class="upload-button ml-4">
                <span wire:loading.remove wire:target="uploadFile">{{ __('Upload File') }}</span>
                <span wire:loading wire:target="uploadFile">{{ __('Uploading') }}...</span>
            </x-primary-button>
        @endif
    </div>
    <style>
        .drop-zone {
            width: max-content;
            min-height: 120px;
            padding: 25px;
            border: 2px dashed #ccc;
            border-radius: 10px;
            text-align: center;
            margin: 20px;
            position: relative;

            & #dropZoneRect {
                stroke: none;
            }
        }

        .drop-zone.dragover {
            border: none;

            & #dropZoneRect {
                stroke: #2484ff;
                stroke-width: 5px;
                animation: spinner-svg-border 2s linear infinite;
            }
        }

        @keyframes spinner-svg-border {
            0% {
                stroke-dashoffset: 0;
            }

            100% {
                stroke-dashoffset: -200%;
            }
        }

        #fileInput {
            display: none;
        }

        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #f8f9fa;
            color: #2484ff;
            margin: 5px 0;
            border-radius: 5px;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropZone = document.getElementById('dropZone');
            const selectButton = document.getElementById('selectButton');
            const fileInput = document.getElementById('fileInput');

            selectButton.addEventListener('click', () => fileInput.click());

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.add('dragover');
                });
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.remove('dragover');
                });
            });

            dropZone.addEventListener('drop', handleDrop);

            function handleDrop(e) {
                const dt = e.dataTransfer;

                fileInput.files = dt.files;

                const event = new Event('change', {bubbles: true});
                fileInput.dispatchEvent(event);
            }
        });
    </script>
</section>
