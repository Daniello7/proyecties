<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class DropZone extends Component
{
    use WithFileUploads;

    public bool $showUploadButton = false;
    public $person;
    public $person_id;
    public $dropZoneFile;
    public $allowedTypeFile;

    // Properties for the file
    public $filename;
    public $original_name;
    public $type;
    public $size;
    public $path;

    protected function rules(): array
    {
        $mimeTypes = $this->allowedTypeFile === 'excel' ? 'xlsx,xls' : 'pdf';

        return [
            'dropZoneFile' => "required|mimes:{$mimeTypes}|max:10240",
            'filename' => 'required|string|max:255',
            'original_name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'size' => 'required|integer',
        ];
    }

    public $dropZoneMessage = 'Drag your file here or click to select it';

    public function mount($person): void
    {
        $this->person = $person;
        $this->person_id = $person->id;
    }

    public function updatedDropZoneFile(): void
    {
        $this->resetExcept(['person', 'person_id', 'dropZoneFile']);

        $validationRule = match ($this->allowedTypeFile) {
            'excel' => ['dropZoneFile' => 'required|mimes:xlsx,xls|max:10240'],
            default => ['dropZoneFile' => 'required|mimes:pdf|max:10240'],
        };

        $this->validate($validationRule);

        $this->dropZoneMessage = '';
        $this->filename = $this->dropZoneFile->getClientOriginalName();
        $this->original_name = $this->dropZoneFile->getClientOriginalName();
        $this->type = $this->dropZoneFile->getMimeType();
        $this->size = $this->dropZoneFile->getSize();
        $this->showUploadButton = true;
    }

    public function uploadFile(): void
    {
        $validated = $this->validate();

        $this->path = $this->dropZoneFile->store('person-documents', 'public');

        $validated['path'] = $this->path;

        $this->person->documents()->create($validated);

        $this->dispatch('uploadedFile');

        $this->resetExcept(['person', 'person_id']);
    }

    public
    function render()
    {
        return view('livewire.drop-zone');
    }
}
