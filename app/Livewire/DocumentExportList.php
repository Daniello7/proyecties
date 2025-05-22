<?php

namespace App\Livewire;

use App\Models\DocumentExport;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Storage;

class DocumentExportList extends Component
{
    public Collection $documentExports;
    public string $type = 'pdf';

    public function mount(): void
    {
        $this->updateDocumentExport();
    }

    public function changeType($type): void
    {
        $this->type = $type;
    }

    private function getDocumentExports(): Collection
    {
        $user_id = auth()->user()->id;

        return DocumentExport::query()
            ->where('user_id', $user_id)
            ->where('type', $this->type)
            ->get();
    }

    public function updateDocumentExport(): void
    {
        $this->documentExports = $this->getDocumentExports();
    }

    public function updateViewedAt(int $id): void
    {
        $document = DocumentExport::findOrFail($id);

        if ($document->viewed_at == null) {
            $document->update(['viewed_at' => now()]);
        }

        $this->dispatch('open-document', documentUrl: Storage::url($document->file_path));
        $this->dispatch('updated-document');
    }

    public function changeViewedAt(int $id): void
    {
        $document = DocumentExport::findOrFail($id);

        $document->update(['viewed_at' => $document->viewed_at == null ? now() : null]);

        $this->dispatch('updated-document');
    }

    public function viewedAll(): void
    {
        $this->documentExports
            ->whereNull('viewed_at')
            ->each(fn($document) => $document->update(['viewed_at' => now()]));

        $this->dispatch('updated-document');
    }

    public function notViewedAll(): void
    {
        $this->documentExports
            ->whereNotNull('viewed_at')
            ->each(fn($document) => $document->update(['viewed_at' => null]));

        $this->dispatch('updated-document');
    }

    public function deletePdf(int $id): void
    {
        $document = DocumentExport::findOrFail($id);
        Storage::disk('public')->delete($document->file_path);
        $document->delete();
        $this->dispatch('updated-document');
    }

    public function render()
    {
        $this->updateDocumentExport();
        return view('livewire.document-export-list');
    }
}
