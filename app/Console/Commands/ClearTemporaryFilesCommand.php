<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ClearTemporaryFilesCommand extends Command
{
    protected $signature = 'files:clear {--f}';

    protected $description = 'Clear test files, pdfs and excels';

    public function handle()
    {
        if (!$this->option('f') && !$this->confirm('¿Clear all temporary testing files?')) {
            $this->info('Canceled clear');
            return CommandAlias::SUCCESS;
        }

        $directories = [
            ['disk' => 'local', 'path' => 'livewire-tmp'],
            ['disk' => 'public', 'path' => 'person-documents'],
            ['disk' => 'public', 'path' => 'pdf'],
            ['disk' => 'public', 'path' => 'excel'],
        ];

        $this->withProgressBar($directories, function ($dir) {
            if (Storage::disk($dir['disk'])->exists($dir['path'])) {
                Storage::disk($dir['disk'])->deleteDirectory($dir['path']);
                $this->newLine();
                $this->info("Directory {$dir['path']} deleted from {$dir['disk']} disk");
            }
        });

        $this->newLine();
        $this->info('Clear completed!');

        return CommandAlias::SUCCESS;
    }
}
