<?php

namespace App\Jobs;

use App\Models\Person;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GeneratePdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Person $person;
    protected string $viewName;

    /**
     * Create a new job instance.
     */
    public function __construct(Person $person, string $viewName)
    {
        $this->person = $person;
        $this->viewName = $viewName;
    }

    /**
     * Execute the job.
     */
    public function handle(): string
    {
        // Generar el PDF
        $pdf = PDF::loadView($this->viewName, ['person' => $this->person]);

        // Nombre del archivo
        $fileName = "pdfs/{$this->person->id}_rules.pdf";

        // Guardar en storage
        Storage::put($fileName, $pdf->output());

        // (Opcional) Retornar el nombre del archivo si necesitas usarlo en otro proceso
        return $fileName;
    }
}
