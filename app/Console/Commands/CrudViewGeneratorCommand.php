<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CrudViewGeneratorCommand extends Command
{
    protected $signature = 'make:crud-view {name} {--layout=app}';
    protected $description = 'Genera vistas CRUD para un recurso';

    public function handle()
    {
        $name = strtolower($this->argument('name'));
        $viewPath = resource_path("views/{$name}");
        $layout = $this->option('layout') ?? 'app';

        if (!File::exists($viewPath)) {
            File::makeDirectory($viewPath, 0755, true);
        }

        $files = ['index', 'show', 'create', 'edit'];

        foreach ($files as $file) {
            $filePath = "{$viewPath}/{$file}.blade.php";
            if (!File::exists($filePath)) {
                File::put($filePath, "<x-{$layout}-layout>\n" .
                    "\t<x-slot name='header'>\n" .
                    "\t\t<h1 class='font-semibold text-3xl custom-gradient-text'>\n" .
                    "\t\t\t{{ __('View $file Header') }}\n" .
                    "\t\t</h1>\n" .
                    "\t</x-slot>\n" .
                    "</x-{$layout}-layout>\n");
            }
        }

        $this->info("Vistas CRUD creadas en resources/views/{$name}/");
    }
}
