<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

// Arrange global
beforeEach(function () {
    Storage::fake('local');
    Storage::fake('public');

    $directories = [
        ['disk' => 'local', 'path' => 'livewire-tmp', 'file' => 'test.txt'],
        ['disk' => 'public', 'path' => 'person-documents', 'file' => 'test.txt'],
        ['disk' => 'public', 'path' => 'pdf', 'file' => 'test.pdf'],
        ['disk' => 'public', 'path' => 'excel', 'file' => 'test.xlsx'],
    ];

    foreach ($directories as $dir) {
        Storage::disk($dir['disk'])->makeDirectory($dir['path']);
        Storage::disk($dir['disk'])->put("{$dir['path']}/{$dir['file']}", 'test');
    }
});

// Helper function para assertions
function assertDirectoriesExist(bool $exists = true) {
    $method = $exists ? 'toBeTrue' : 'toBeFalse';
    expect(Storage::disk('local')->directoryExists('livewire-tmp'))->$method();
    expect(Storage::disk('public')->directoryExists('person-documents'))->$method();
    expect(Storage::disk('public')->directoryExists('pdf'))->$method();
    expect(Storage::disk('public')->directoryExists('excel'))->$method();
}

it('clears all directories when using force option', function () {
    // Act
    Artisan::call('files:clear', ['--f' => true]);

    // Assert
    assertDirectoriesExist(false);

    // Act & Assert
    $this->artisan('files:clear', ['--f' => true])
        ->assertSuccessful()
        ->expectsOutput('Clear completed!');
});

it('clears all directories when user confirms', function () {
    // Act & Assert
    $this->artisan('files:clear')
        ->expectsConfirmation('¿Clear all temporary testing files?', 'yes')
        ->assertSuccessful()
        ->expectsOutput('Clear completed!');

    // Assert
    assertDirectoriesExist(false);
});

it('cancels operation when user denies confirmation', function () {
    // Act & Assert
    $this->artisan('files:clear')
        ->expectsConfirmation('¿Clear all temporary testing files?', 'no')
        ->assertSuccessful()
        ->expectsOutput('Canceled clear');

    // Assert
    assertDirectoriesExist(true);
});

it('shows message only for existing directories', function () {
    // Arrange
    Storage::disk('public')->deleteDirectory('pdf');
    Storage::disk('public')->deleteDirectory('excel');

    // Act & Assert
    $this->artisan('files:clear', ['--f' => true])
        ->assertSuccessful()
        ->expectsOutput('Directory livewire-tmp deleted from local disk')
        ->expectsOutput('Directory person-documents deleted from public disk')
        ->doesntExpectOutput('Directory pdf deleted from public disk')
        ->doesntExpectOutput('Directory excel deleted from public disk')
        ->expectsOutput('Clear completed!');

    // Assert
    expect(Storage::disk('local')->directoryExists('livewire-tmp'))->toBeFalse();
    expect(Storage::disk('public')->directoryExists('person-documents'))->toBeFalse();
});