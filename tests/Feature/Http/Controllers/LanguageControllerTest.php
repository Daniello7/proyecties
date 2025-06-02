<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

beforeEach(function () {
    Session::start();
});

it('sets language in session when valid language is provided', function () {
    // Arrange
    File::shouldReceive('directories')
        ->once()
        ->with(lang_path())
        ->andReturn(['path/to/es', 'path/to/en']);

    // Act
    $response = $this->from('/previous-page')
        ->get(route('languages', ['lang' => 'es']));

    // Assert
    $response->assertRedirect('/previous-page');
    expect(session('lang'))->toBe('es');
    expect(App::getLocale())->toBe('es');
});

it('does set default es language in session when invalid language is provided', function () {
    // Arrange
    File::shouldReceive('directories')
        ->once()
        ->with(lang_path())
        ->andReturn(['path/to/es', 'path/to/en']);

    // Act
    $response = $this->from('/previous-page')
        ->get(route('languages', ['lang' => 'invalid']));

    // Assert
    $response->assertRedirect('/previous-page');
    expect(session('lang'))->toBe('es');
    expect(App::getLocale())->toBe('es');
});

it('returns to previous page after language update', function () {
    // Arrange
    File::shouldReceive('directories')
        ->once()
        ->with(lang_path())
        ->andReturn(['path/to/es', 'path/to/en']);

    // Act
    $response = $this->from('/test-page')
        ->get(route('languages', ['lang' => 'en']));

    // Assert
    $response->assertRedirect('/test-page');
    expect(session('lang'))->toBe('en');
});