<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    File::shouldReceive('exists')->andReturn(false);
    File::shouldReceive('makeDirectory')->andReturn(true);
    File::shouldReceive('put')->andReturn(true);
});

it('creates CRUD view files successfully', function () {
    // Act
    Artisan::call('make:crud-view TestResource');
    $output = Artisan::output();

    // Assert
    expect($output)->toContain('Vistas CRUD creadas en resources/views/testresource/');
});
