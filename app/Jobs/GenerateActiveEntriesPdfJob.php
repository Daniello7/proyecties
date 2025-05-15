<?php

namespace App\Jobs;

use App\Events\GeneratedActiveEntriesPdfEvent;
use App\Models\PdfExport;
use App\Models\PersonEntry;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerateActiveEntriesPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $columns;
    public array $entries_id;
    public int $user_id;

    public function __construct(array $columns, array $entries_id, int $user_id)
    {
        $this->columns = $columns;
        $this->entries_id = $entries_id;
        $this->user_id = $user_id;
    }

    public function handle(): void
    {
        array_pop($this->columns);
        array_splice($this->columns, 3, 0, ['Reason', 'Date entry']);

        $entries = PersonEntry::with([
            'person:id,name,last_name,company',
            'internalPerson:id,person_id',
            'internalPerson.person:id,name,last_name',
        ])
            ->whereIn('id', $this->entries_id)
            ->get();

        $user = User::findOrFail($this->user_id);

        $pdf = PDF::loadView('pdf.activeEntries', [
            'columns' => $this->columns,
            'entries' => $entries,
            'username' => $user->name,
        ]);

        $filename = 'pdfs/active_entries_' . $this->user_id . '_' . now()->timestamp . '.pdf';

        Storage::disk('public')->put($filename, $pdf->output());

        PdfExport::create([
            'user_id' => $this->user_id,
            'file_path' => $filename,
            'type' => 'Active Entries',
        ]);

        event(new GeneratedActiveEntriesPdfEvent($this->user_id));
    }
}
