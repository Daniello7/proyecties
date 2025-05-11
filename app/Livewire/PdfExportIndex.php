<?php

namespace App\Livewire;

use App\Models\PdfExport;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class PdfExportIndex extends Component
{
    public Collection $pdfsExport;

    public function mount(): void
    {
        $this->pdfsExport = $this->getPdfsExport();
    }

    public function getPdfsExport(): Collection
    {
        $user_id = auth()->user()->id;

        return PdfExport::where('user_id', $user_id)->get();
    }

    public function render()
    {
        return view('livewire.pdf-export');
    }
}
