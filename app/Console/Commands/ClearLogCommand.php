<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearLogCommand extends Command
{
    protected $signature = 'log:clear';

    protected $description = 'Clear the laravel log file, storage/logs/laravel.log';

    public function handle(): void
    {
        $logPath = storage_path('logs/laravel.log');

        if (file_exists($logPath)) {
            file_put_contents($logPath, '');
            $this->info("\t" . __('Laravel log cleared successfully.'));
        } else {
            $this->warn("\t" . __('laravel.log file does not exist.'));
        }
    }
}
