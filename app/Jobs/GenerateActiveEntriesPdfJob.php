<?php

namespace App\Jobs;

use App\Models\DocumentExport;
use App\Models\PersonEntry;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Str;

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

        $filename = 'Active Entries';

        $filePath = sprintf(
            'pdf/exports/%s/%s_%s_%s.pdf',
            now()->format('Y-m'),
            "user_$this->user_id",
            Str::slug(strtolower($filename)),
            Str::uuid()
        );

        DocumentExport::create([
            'user_id' => $this->user_id,
            'filename' => $filename,
            'file_path' => $filePath,
            'type' => 'pdf',
        ]);

        Storage::disk('public')->put($filePath, $pdf->output());

    }
}
