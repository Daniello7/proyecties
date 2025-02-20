<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class BuildAndClearAllCommand extends Command
{
    protected $signature = 'cb';

    protected $description = 'Clean all caches and execute npm run build commands';

    public function handle(): int
    {
        $this->info('🧹 Cleaning caches...');
        $this->call('view:clear');
        $this->call('config:clear');
        $this->call('cache:clear');
        $this->call('route:clear');

        $this->info('⚙️ Executing npm run build...');
        $npmCommand = 'npm run build';

        $process = Process::fromShellCommandline($npmCommand);
        $process->setWorkingDirectory(base_path()); // Root location of the project

        // It shows NPM Run Build actions
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        if (!$process->isSuccessful()) {
            $this->error('❌ Error executing: ' . $npmCommand);
            return 1; // 1 is failure
        }

        $this->info('✓ Process executed correctly.');
        return 0; // 0 is success
    }
}
