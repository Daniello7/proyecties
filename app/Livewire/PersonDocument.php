<?php

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Component;

class PersonDocument extends Component
{
    public $person;
    public $showDocuments = false;
    protected $listeners = ['uploadedFile' => 'uploadedFile'];

    public function updateShowDocuments(): void
    {
        $this->showDocuments = !$this->showDocuments;
    }

    public function uploadedFile(): void
    {
        session()->flash('document-status', __('messages.document_created'));
    }

    public function deleteDocument($id): void
    {
        $file = $this->person->documents()->find($id);

        \Storage::disk('public')->delete($file->path);
        $file->delete();

        session()->flash('document-status', __('messages.document_deleted'));
    }

    public function render(): View
    {
        $this->person->load(['documents' => fn($q) => $q->orderByDesc('created_at')]);

        return view('livewire.person-document');
    }
}
