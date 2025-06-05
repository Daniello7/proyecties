<?php

namespace Tests\Unit\Jobs;

use App\Jobs\ForceDeletePackageJob;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    Artisan::shouldReceive('call')
        ->with('packages:clear-old')
        ->once();
});

it('calls the packages:clear-old artisan command', function () {
    // Act
    (new ForceDeletePackageJob())->handle();
});

it('can be dispatched', function () {
    // Arrange & Act & Assert
    expect(fn() => ForceDeletePackageJob::dispatch())
        ->not()->toThrow(Exception::class);
});