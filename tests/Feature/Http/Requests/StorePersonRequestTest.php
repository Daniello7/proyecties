<?php

namespace Tests\Feature\Http\Requests\Person;

use App\Http\Requests\Person\StorePersonRequest;
use Illuminate\Support\Facades\Validator;

it('passes validation when all fields are correct', function () {
    // Arrange
    $data = [
        'document_number' => '123456789',
        'name' => 'John',
        'last_name' => 'Doe',
        'company' => 'Tech Corp',
        'comment' => 'New employee',
    ];

    // Act
    $validator = Validator::make($data, (new StorePersonRequest())->rules());

    // Assert
    expect($validator->fails())->toBeFalse();
});

it('fails validation when document_number is missing', function () {
    // Arrange
    $data = [
        'name' => 'John',
        'last_name' => 'Doe',
        'company' => 'Tech Corp',
        'comment' => 'New employee',
    ];

    // Act
    $validator = Validator::make($data, (new StorePersonRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('document_number'))->toBeTrue();
});

it('fails validation when name is too long', function () {
    // Arrange
    $data = [
        'document_number' => '123456789',
        'name' => str_repeat('A', 101),
        'last_name' => 'Doe',
        'company' => 'Tech Corp',
        'comment' => 'New employee',
    ];

    // Act
    $validator = Validator::make($data, (new StorePersonRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('name'))->toBeTrue();
});

it('fails validation when comment exceeds max length', function () {
    // Arrange
    $data = [
        'document_number' => '123456789',
        'name' => 'John',
        'last_name' => 'Doe',
        'company' => 'Tech Corp',
        'comment' => str_repeat('A', 256),
    ];

    // Act
    $validator = Validator::make($data, (new StorePersonRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('comment'))->toBeTrue();
});
