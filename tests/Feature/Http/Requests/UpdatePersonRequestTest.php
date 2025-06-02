<?php

namespace Tests\Feature\Http\Requests\Person;

use App\Http\Requests\Person\UpdatePersonRequest;
use Illuminate\Support\Facades\Validator;

it('passes validation when all fields are correct', function () {
    // Arrange
    $data = [
        'document_number' => '123456789',
        'name' => 'John',
        'last_name' => 'Doe',
        'company' => 'Tech Corp',
        'comment' => 'Updated employee',
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonRequest())->rules());

    // Assert
    expect($validator->passes())->toBeTrue();
});

it('passes validation when comment is null', function () {
    // Arrange
    $data = [
        'document_number' => '123456789',
        'name' => 'John',
        'last_name' => 'Doe',
        'company' => 'Tech Corp',
        'comment' => null,
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonRequest())->rules());

    // Assert
    expect($validator->passes())->toBeTrue();
});

it('fails validation when document_number is missing', function () {
    // Arrange
    $data = [
        'name' => 'John',
        'last_name' => 'Doe',
        'company' => 'Tech Corp',
        'comment' => 'Updated employee',
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('document_number'))->toBeTrue();
});

it('fails validation when document_number is not a string', function () {
    // Arrange
    $data = [
        'document_number' => ['not-a-string'],
        'name' => 'John',
        'last_name' => 'Doe',
        'company' => 'Tech Corp',
        'comment' => 'Updated employee',
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('document_number'))->toBeTrue();
});

it('fails validation when document_number exceeds max length', function () {
    // Arrange
    $data = [
        'document_number' => str_repeat('1', 21),
        'name' => 'John',
        'last_name' => 'Doe',
        'company' => 'Tech Corp',
        'comment' => 'Updated employee',
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('document_number'))->toBeTrue();
});

it('fails validation when name is missing', function () {
    // Arrange
    $data = [
        'document_number' => '123456789',
        'last_name' => 'Doe',
        'company' => 'Tech Corp',
        'comment' => 'Updated employee',
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('name'))->toBeTrue();
});

it('fails validation when name is not a string', function () {
    // Arrange
    $data = [
        'document_number' => '123456789',
        'name' => ['not-a-string'],
        'last_name' => 'Doe',
        'company' => 'Tech Corp',
        'comment' => 'Updated employee',
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('name'))->toBeTrue();
});

it('fails validation when name exceeds max length', function () {
    // Arrange
    $data = [
        'document_number' => '123456789',
        'name' => str_repeat('A', 101),
        'last_name' => 'Doe',
        'company' => 'Tech Corp',
        'comment' => 'Updated employee',
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('name'))->toBeTrue();
});

it('fails validation when last_name is missing', function () {
    // Arrange
    $data = [
        'document_number' => '123456789',
        'name' => 'John',
        'company' => 'Tech Corp',
        'comment' => 'Updated employee',
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('last_name'))->toBeTrue();
});

it('fails validation when last_name is not a string', function () {
    // Arrange
    $data = [
        'document_number' => '123456789',
        'name' => 'John',
        'last_name' => ['not-a-string'],
        'company' => 'Tech Corp',
        'comment' => 'Updated employee',
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('last_name'))->toBeTrue();
});

it('fails validation when last_name exceeds max length', function () {
    // Arrange
    $data = [
        'document_number' => '123456789',
        'name' => 'John',
        'last_name' => str_repeat('A', 101),
        'company' => 'Tech Corp',
        'comment' => 'Updated employee',
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('last_name'))->toBeTrue();
});

it('fails validation when company is missing', function () {
    // Arrange
    $data = [
        'document_number' => '123456789',
        'name' => 'John',
        'last_name' => 'Doe',
        'comment' => 'Updated employee',
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('company'))->toBeTrue();
});

it('fails validation when company is not a string', function () {
    // Arrange
    $data = [
        'document_number' => '123456789',
        'name' => 'John',
        'last_name' => 'Doe',
        'company' => ['not-a-string'],
        'comment' => 'Updated employee',
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('company'))->toBeTrue();
});

it('fails validation when company exceeds max length', function () {
    // Arrange
    $data = [
        'document_number' => '123456789',
        'name' => 'John',
        'last_name' => 'Doe',
        'company' => str_repeat('A', 101),
        'comment' => 'Updated employee',
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('company'))->toBeTrue();
});

it('fails validation when comment is not a string', function () {
    // Arrange
    $data = [
        'document_number' => '123456789',
        'name' => 'John',
        'last_name' => 'Doe',
        'company' => 'Tech Corp',
        'comment' => ['not-a-string'],
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('comment'))->toBeTrue();
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
    $validator = Validator::make($data, (new UpdatePersonRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('comment'))->toBeTrue();
});

it('verifies authorize method returns true', function () {
    // Arrange
    $request = new UpdatePersonRequest();

    // Act & Assert
    expect($request->authorize())->toBeTrue();
});