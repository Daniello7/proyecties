<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    if (!is_dir(storage_path('logs'))) {
        mkdir(storage_path('logs'), 0755, true);
    }
});

afterEach(function () {
    $logPath = storage_path('logs/laravel.log');
    if (file_exists($logPath)) {
        unlink($logPath);
    }
});

it('clears log file when it exists', function () {
    // Arrange
    $logPath = storage_path('logs/laravel.log');
    File::put($logPath, 'Test log content');
    
    // Act
    Artisan::call('log:clear');
    
    // Assert
    expect(file_get_contents($logPath))->toBe('');
    $this->artisan('log:clear')->expectsOutput("\t" . __('Laravel log cleared successfully.'));
});

it('shows warning when log file does not exist', function () {
    // Arrange
    $logPath = storage_path('logs/laravel.log');
    File::delete($logPath);
    
    // Act & Assert
    $this->artisan('log:clear')
        ->assertSuccessful()
        ->expectsOutput("\t" . __('laravel.log file does not exist.'));
});