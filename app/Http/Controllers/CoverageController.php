<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class CoverageController extends Controller
{
    private array $mimeTypes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'html' => 'text/html',
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
    ];

    public function __invoke($path = 'index.html')
    {
        if (!Storage::disk('local')->exists('coverage/' . $path)) {
            abort(404, 'Reporte no encontrado');
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $mimeType = $this->mimeTypes[$extension] ?? 'text/plain';

        return response()->file(
            Storage::disk('local')->path('coverage/' . $path),
            ['Content-Type' => $mimeType]
        );
    }
}