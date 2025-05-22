<?php

namespace App\Jobs;

use App\Exports\PersonEntryExport;
use App\Models\DocumentExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Str;

class GenerateActiveEntriesExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $entries_id;
    public int $user_id;

    public function __construct(array $entries_id, int $user_id)
    {
        $this->entries_id = $entries_id;
        $this->user_id = $user_id;
    }

    public function handle(): void
    {
        $filename = 'Active Entries';
        $filePath = sprintf(
            'excel/exports/%s/%s_%s_%s.pdf',
            now()->format('Y-m'),
            "user_$this->user_id",
            Str::slug(strtolower($filename)),
            Str::uuid()
        );

        DocumentExport::create([
            'user_id' => $this->user_id,
            'filename' => $filename,
            'file_path' => $filePath,
            'type' => 'xlsx',
        ]);

        Excel::store(
            new PersonEntryExport($this->entries_id),
            $filePath, 'public'
        );

        session()->flash('success', __('messages.document_created'));
    }
}
