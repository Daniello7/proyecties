<?php

use App\Mail\NotifyContactMail;
use App\Models\Person;
use App\Models\PersonEntry;
use App\Models\User;
use App\Models\InternalPerson;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

beforeEach(function () {
    $this->user = actingAsPorter();
});

it('creates a person entry successfully', function () {
    // Arrange
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create();

    Mail::fake();

    $data = [
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'reason' => 'Charge',
        'arrival_time' => Carbon::now(),
        'entry_time' => Carbon::now(),
        'exit_time' => Carbon::now()->addHours(1),
        'comment' => 'Entry for charge',
        'notify' => true
    ];

    // Act
    $response = $this->post(route('person-entries.store'), $data);

    // Assert
    $response->assertStatus(200);
    $response->assertJson(['message' => __('messages.person-entry_created')]);
    $response->assertJson(['pdfUrl' => route('driver-rules', ['person' => $person])]);
    $this->assertDatabaseHas('person_entries', [
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'reason' => 'Charge',
        'comment' => 'Entry for charge',
    ]);
    Mail::assertQueued(NotifyContactMail::class);
});

it('updates a person entry successfully', function () {
    // Arrange
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create();
    $personEntry = PersonEntry::factory()->create();

    $data = [
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'comment' => 'Updated comment for entry',
        'reason' => 'Discharge',
        'arrival_time' => Carbon::now(),
        'entry_time' => Carbon::now(),
        'exit_time' => Carbon::now()->addHours(2),
    ];

    // Act
    $response = $this->put(route('person-entries.update', $personEntry->id), $data);

    // Assert
    $response->assertRedirect(route('control-access'));
    $response->assertSessionHas('status', __('messages.person-entry_updated'));
    $this->assertDatabaseHas('person_entries', [
        'id' => $personEntry->id,
        'comment' => 'Updated comment for entry',
        'reason' => 'Discharge',
    ]);
});

it('deletes a person entry successfully', function () {
    // Arrange
    Person::factory()->create();
    InternalPerson::factory()->create();
    $personEntry = PersonEntry::factory()->create();

    // Act & Assert
    $this->delete(route('person-entries.destroy', $personEntry->id))
        ->assertStatus(403);

    // Arrange
    actingAsAdmin();

    // Act
    $response = $this->delete(route('person-entries.destroy', $personEntry->id));

    // Assert
    $response->assertRedirect(route('control-access'));
    $response->assertSessionHas('status', __('messages.person-entry_deleted'));
    $this->assertDatabaseMissing('person_entries', [
        'id' => $personEntry->id,
    ]);
});

it('shows a person entry edit page successfully', function () {
    // Arrange
    Person::factory()->create();
    InternalPerson::factory()->create();
    $personEntry = PersonEntry::factory()->create();

    // Act
    $response = $this->get(route('person-entries.edit', $personEntry->id));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('person-entry.edit');
    $response->assertViewHas('personEntry', $personEntry);
});

it('returns a 404 if the person entry does not exist', function () {
    // Arrange
    $nonExistentId = 0;

    // Act
    $response = $this->get(route('person-entries.edit', $nonExistentId));

    // Assert
    $response->assertStatus(404);
});

it('renders the person entry creation page successfully', function () {
    // Arrange
    $person = Person::factory()->create();

    // Act
    $response = $this->get(route('person-entries.create', ['person_id' => $person->id]));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('person-entry.create');
    $response->assertViewHas('person');
    $response->assertViewHas('lastEntry');
});

it('has not permission to see create view', function () {
    // Arrange
    $person = Person::factory()->create();
    $user = User::factory()->create();
    loginAsUser($user);

    // Act & Assert
    $this->get(route('person-entries.create', ['person_id' => $person->id]))
        ->assertStatus(403);

    $this->assertFalse($user->can('create', PersonEntry::class));

});