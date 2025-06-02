<?php

use App\Models\Person;
use App\Models\InternalPerson;
use App\Models\PersonEntry;
use App\Http\Requests\PersonEntry\StorePersonEntryRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->person = Person::factory()->create();
    $this->internalPerson = InternalPerson::factory()->create();
});

it('validates with correct data', function () {
    $data = [
        'person_id' => $this->person->id,
        'internal_person_id' => $this->internalPerson->id,
        'reason' => PersonEntry::REASONS[0],
        'comment' => 'Test comment'
    ];

    $validator = Validator::make($data, (new StorePersonEntryRequest())->rules());

    expect($validator->passes())->toBeTrue();
});

it('requires person_id', function () {
    $data = [
        'internal_person_id' => $this->internalPerson->id,
        'reason' => PersonEntry::REASONS[0],
        'comment' => 'Test comment'
    ];

    $validator = Validator::make($data, (new StorePersonEntryRequest())->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('person_id'))->toBeTrue();
});

it('requires internal_person_id', function () {
    $data = [
        'person_id' => $this->person->id,
        'reason' => PersonEntry::REASONS[0],
        'comment' => 'Test comment'
    ];

    $validator = Validator::make($data, (new StorePersonEntryRequest())->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('internal_person_id'))->toBeTrue();
});

it('requires reason', function () {
    $data = [
        'person_id' => $this->person->id,
        'internal_person_id' => $this->internalPerson->id,
        'comment' => 'Test comment'
    ];

    $validator = Validator::make($data, (new StorePersonEntryRequest())->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('reason'))->toBeTrue();
});

it('validates reason must be in valid reasons list', function () {
    $data = [
        'person_id' => $this->person->id,
        'internal_person_id' => $this->internalPerson->id,
        'reason' => 'invalid_reason',
        'comment' => 'Test comment'
    ];

    $validator = Validator::make($data, (new StorePersonEntryRequest())->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('reason'))->toBeTrue();
});

it('accepts null comment', function () {
    $data = [
        'person_id' => $this->person->id,
        'internal_person_id' => $this->internalPerson->id,
        'reason' => PersonEntry::REASONS[0],
        'comment' => null
    ];

    $validator = Validator::make($data, (new StorePersonEntryRequest())->rules());

    expect($validator->passes())->toBeTrue();
});

it('validates person_id must exist', function () {
    $data = [
        'person_id' => 999999,
        'internal_person_id' => $this->internalPerson->id,
        'reason' => PersonEntry::REASONS[0],
        'comment' => 'Test comment'
    ];

    $validator = Validator::make($data, (new StorePersonEntryRequest())->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('person_id'))->toBeTrue();
});

it('validates internal_person_id must exist', function () {
    $data = [
        'person_id' => $this->person->id,
        'internal_person_id' => 999999,
        'reason' => PersonEntry::REASONS[0],
        'comment' => 'Test comment'
    ];

    $validator = Validator::make($data, (new StorePersonEntryRequest())->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('internal_person_id'))->toBeTrue();
});