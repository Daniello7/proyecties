<?php

use App\Models\Area;
use App\Models\Key;
use App\Models\KeyControl;
use Illuminate\Database\Eloquent\Collection;

it('automatically increments area_key_number when creating a key', function () {
    // Arrange
    $area = Area::factory()->create();
    $firstKey = Key::factory()->create(['area_id' => $area->id]);
    
    // Act
    $secondKey = Key::factory()->create(['area_id' => $area->id]);
    
    // Assert
    expect($firstKey->area_key_number)->toBe(1)
        ->and($secondKey->area_key_number)->toBe(2);
});

it('creates first key with area_key_number 1 when no previous keys exist', function () {
    // Arrange
    $area = Area::factory()->create();
    
    // Act
    $key = Key::factory()->create(['area_id' => $area->id]);
    
    // Assert
    expect($key->area_key_number)->toBe(1);
});

it('belongs to an area', function () {
    // Arrange
    $area = Area::factory()->create();
    $key = Key::factory()->create(['area_id' => $area->id]);
    
    // Act & Assert
    expect($key->area)->toBeInstanceOf(Area::class)
        ->and($key->area->id)->toBe($area->id);
});

it('has many key controls', function () {
    // Arrange
    $key = Key::factory()->create();
    KeyControl::factory()->count(3)->create(['key_id' => $key->id]);
    
    // Act & Assert
    expect($key->keyControls)
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(3)
        ->each->toBeInstanceOf(KeyControl::class);
});

it('is mass assignable only for name attribute', function () {
    // Arrange & Act
    $key = new Key();
    
    // Assert
    expect($key->getFillable())->toBe(['name']);
});