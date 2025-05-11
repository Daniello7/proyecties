<?php

namespace App\Livewire;

use App\Models\PdfExport;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Storage;

class PdfExportIndex extends Component
{
    public Collection $pdfsExport;

    public function mount(): void
    {
        $this->updatePdfsExport();
    }

    public function getPdfsExport(): Collection
    {
        $user_id = auth()->user()->id;

        return PdfExport::where('user_id', $user_id)->get();
    }

    public function updatePdfsExport(): void
    {
        $this->pdfsExport = $this->getPdfsExport();
    }

    public function updateViewedAt(int $id): void
    {
        $pdf = PdfExport::findOrFail($id);

        if ($pdf->viewed_at == null) {
            $pdf->update(['viewed_at' => now()]);
        }

        $this->dispatch('open-pdf', pdfUrl: Storage::url($pdf->file_path));
        $this->dispatch('updated-pdf');
    }

    public function deletePdf(int $id): void
    {
        $pdf = PdfExport::findOrFail($id);
        Storage::disk('public')->delete($pdf->file_path);
        $pdf->delete();
        $this->dispatch('updated-pdf');
    }

    public function render()
    {
        $this->updatePdfsExport();
        return view('livewire.pdf-export');
    }
}
