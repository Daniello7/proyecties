<?php

use App\Console\Commands\BuildAndClearAllCommand;
use Symfony\Component\Process\Process;

it('works correctly', function () {
    $mockProcess = Mockery::mock('overload:' . Process::class);
    $mockProcess->shouldReceive('fromShellCommandline')
        ->with('npm run build')
        ->andReturnSelf();
    $mockProcess->shouldReceive('setWorkingDirectory')
        ->with(base_path())
        ->andReturnSelf();
    $mockProcess->shouldReceive('run');
    $mockProcess->shouldReceive('isSuccessful')
        ->andReturn(true);

    $this->artisan('cb')
        ->expectsOutput('🧹 Cleaning caches...')
        ->expectsOutput('⚙️ Executing npm run build...')
        ->expectsOutput('✓ Process executed correctly.')
        ->assertExitCode(0);
});


it('verifies command signature and description', function () {
    $command = new BuildAndClearAllCommand();

    expect($command->getName())->toBe('cb')
        ->and($command->getDescription())->toBe('Clean all caches and execute npm run build commands');
});

it('returns error code when process fails', function () {
    $mockProcess = Mockery::mock('overload:' . Process::class);
    $mockProcess->shouldReceive('fromShellCommandline')
        ->with('npm run build')
        ->andReturnSelf();
    $mockProcess->shouldReceive('setWorkingDirectory')
        ->with(base_path())
        ->andReturnSelf();
    $mockProcess->shouldReceive('run');
    $mockProcess->shouldReceive('isSuccessful')
        ->andReturn(false);

    $this->artisan('cb')
        ->expectsOutput('❌ Error executing: npm run build')
        ->assertExitCode(1);
});







