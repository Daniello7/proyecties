<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\StorePackageReceptionRequest;
use App\Models\InternalPerson;
use App\Models\Person;
use Illuminate\Support\Facades\Validator;

it('passes validation when all fields are correct', function () {
    // Arrange
    Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create();

    $data = [
        'agency' => 'Agency 1',
        'external_entity' => 'Entity A',
        'internal_person_id' => $internalPerson->id,
        'package_count' => 5,
        'comment' => 'Package received',
    ];

    // Act
    $validator = Validator::make($data, (new StorePackageReceptionRequest())->rules());

    // Assert
    expect($validator->fails())->toBeFalse();
});

it('fails validation when agency is missing', function () {
    // Arrange
    $data = [
        'external_entity' => 'Entity A',
        'internal_person_id' => 1,
        'package_count' => 5,
        'comment' => 'Package received',
    ];

    // Act
    $validator = Validator::make($data, (new StorePackageReceptionRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('agency'))->toBeTrue();
});

it('fails validation when external_entity is missing', function () {
    // Arrange
    $data = [
        'agency' => 'Agency 1',
        'internal_person_id' => 1,
        'package_count' => 5,
        'comment' => 'Package received',
    ];

    // Act
    $validator = Validator::make($data, (new StorePackageReceptionRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('external_entity'))->toBeTrue();
});

it('fails validation when internal_person_id is invalid', function () {
    // Arrange
    $data = [
        'agency' => 'Agency 1',
        'external_entity' => 'Entity A',
        'internal_person_id' => 9999, // Invalid ID
        'package_count' => 5,
        'comment' => 'Package received',
    ];

    // Act
    $validator = Validator::make($data, (new StorePackageReceptionRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('internal_person_id'))->toBeTrue();
});

it('fails validation when package_count is missing', function () {
    // Arrange
    $data = [
        'agency' => 'Agency 1',
        'external_entity' => 'Entity A',
        'internal_person_id' => 1,
        'comment' => 'Package received',
    ];

    // Act
    $validator = Validator::make($data, (new StorePackageReceptionRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('package_count'))->toBeTrue();
});
