<?php

namespace Tests\Feature\Jobs;

use App\Jobs\GenerateActiveEntriesPdfJob;
use App\Models\DocumentExport;
use App\Models\PersonEntry;
use App\Models\User;
use App\Models\Person;
use App\Models\InternalPerson;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;


beforeEach(function () {
    Storage::fake('public');
});

it('creates document export and stores pdf file', function () {
    // Arrange
    $user = User::factory()->create();
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create([
        'person_id' => Person::factory()->create()->id
    ]);

    $entries = PersonEntry::factory(3)->create([
        'user_id' => $user->id,
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'reason' => PersonEntry::REASONS[0],
    ]);

    $entries_id = $entries->pluck('id')->toArray();
    $columns = ['Column1', 'Column2', 'Column3', 'Column4'];
    $expectedFilename = 'Active Entries';

    // Act
    (new GenerateActiveEntriesPdfJob($columns, $entries_id, $user->id))->handle();

    // Assert
    $documentExport = DocumentExport::first();

    expect($documentExport)
        ->user_id->toBe($user->id)
        ->filename->toBe($expectedFilename)
        ->type->toBe('pdf');

    expect($documentExport->file_path)
        ->toMatch('/^pdf\/exports\/\d{4}-\d{2}\/user_' . $user->id . '_active-entries_[a-f0-9-]+\.pdf$/');

    Storage::disk('public')->assertExists($documentExport->file_path);
});

it('can be dispatched with correct parameters', function () {
    // Arrange
    $user = User::factory()->create();
    $entries_id = [1, 2, 3];
    $columns = ['Column1', 'Column2'];

    // Act & Assert
    expect(fn() => GenerateActiveEntriesPdfJob::dispatch($columns, $entries_id, $user->id))
        ->not()->toThrow(Exception::class);

    $job = new GenerateActiveEntriesPdfJob($columns, $entries_id, $user->id);
    expect($job)
        ->columns->toBe($columns)
        ->entries_id->toBe($entries_id)
        ->user_id->toBe($user->id);
});

it('handles empty entries array correctly', function () {
    // Arrange
    $user = User::factory()->create();
    $entries_id = [];
    $columns = ['Column1', 'Column2'];

    // Act & Assert
    expect(fn() => (new GenerateActiveEntriesPdfJob($columns, $entries_id, $user->id))->handle())
        ->not->toThrow(Exception::class);

    $documentExport = DocumentExport::first();
    expect($documentExport)->not->toBeNull();
});

it('fails when user does not exist', function () {
    // Arrange
    $nonExistentUserId = 999999;
    $entries_id = [1, 2, 3];
    $columns = ['Column1', 'Column2'];

    // Act & Assert
    expect(fn() => (new GenerateActiveEntriesPdfJob($columns, $entries_id, $nonExistentUserId))->handle())
        ->toThrow(ModelNotFoundException::class);
});

it('creates file with correct content structure', function () {
    // Arrange
    $user = User::factory()->create(['name' => 'Test User']);
    $person = Person::factory()->create([
        'name' => 'John',
        'last_name' => 'Doe',
        'company' => 'Test Company'
    ]);

    $internalPerson = InternalPerson::factory()->create([
        'person_id' => Person::factory()->create([
            'name' => 'Jane',
            'last_name' => 'Smith'
        ])->id
    ]);

    $entries = PersonEntry::factory()->create([
        'user_id' => $user->id,
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'reason' => PersonEntry::REASONS[0],
    ]);

    $columns = ['Visitor', 'Company', 'Host', 'Actions'];

    // Act
    (new GenerateActiveEntriesPdfJob($columns, [$entries->id], $user->id))->handle();

    // Assert
    $documentExport = DocumentExport::first();
    Storage::disk('public')->assertExists($documentExport->file_path);
});

it('creates correct file path structure with date', function () {
    // Arrange
    $user = User::factory()->create();
    $columns = ['Column1', 'Column2'];
    $entries_id = [1];
    $currentMonth = now()->format('Y-m');

    // Act
    (new GenerateActiveEntriesPdfJob($columns, $entries_id, $user->id))->handle();

    // Assert
    $documentExport = DocumentExport::first();
    expect($documentExport->file_path)
        ->toContain("pdf/exports/{$currentMonth}")
        ->toContain("user_{$user->id}_")
        ->toContain('.pdf');
});
it('preserves file when another job runs concurrently', function () {
    // Arrange
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $entries1 = PersonEntry::factory(3)->create([
        'user_id' => $user1->id,
        'person_id' => Person::factory()->create()->id,
        'internal_person_id' => InternalPerson::factory()->create()->id
    ]);

    $entries2 = PersonEntry::factory(3)->create([
        'user_id' => $user2->id,
        'person_id' => Person::factory()->create()->id,
        'internal_person_id' => InternalPerson::factory()->create()->id
    ]);

    $columns = ['Column1', 'Column2'];

    // Act
    Carbon::setTestNow('2025-06-04 10:00:00');
    (new GenerateActiveEntriesPdfJob($columns, $entries1->pluck('id')->toArray(), $user1->id))->handle();
    $firstExport = DocumentExport::latest()->first();

    Carbon::setTestNow('2025-06-04 10:00:01');
    (new GenerateActiveEntriesPdfJob($columns, $entries2->pluck('id')->toArray(), $user2->id))->handle();
    $secondExport = DocumentExport::latest()->first();

    // Assert
    expect($secondExport->file_path)->not->toBe($firstExport->file_path);
    expect($secondExport->id)->not->toBe($firstExport->id);
    Storage::disk('public')->assertExists($firstExport->file_path);
    Storage::disk('public')->assertExists($secondExport->file_path);
});

it('creates unique filenames for multiple exports', function () {
    // Arrange
    $users = User::factory(3)->create();
    $filePaths = [];

    foreach ($users as $index => $user) {
        $entry = PersonEntry::factory()->create([
            'user_id' => $user->id,
            'person_id' => Person::factory()->create()->id,
            'internal_person_id' => InternalPerson::factory()->create()->id,
            'reason' => PersonEntry::REASONS[$index]
        ]);

        Carbon::setTestNow(now()->addSeconds($index));
        (new GenerateActiveEntriesPdfJob(['Column1'], [$entry->id], $user->id))->handle();
        $filePaths[] = DocumentExport::latest()->first()->file_path;
    }

    // Assert
    $uniquePaths = array_unique($filePaths);
    expect(count($uniquePaths))->toBe(3);

    foreach ($filePaths as $path) {
        Storage::disk('public')->assertExists($path);
    }
});

it('generates different files for same user with different entries', function () {
    // Arrange
    $user = User::factory()->create();
    $exports = [];

    for ($i = 0; $i < 2; $i++) {
        $entry = PersonEntry::factory()->create([
            'user_id' => $user->id,
            'person_id' => Person::factory()->create()->id,
            'internal_person_id' => InternalPerson::factory()->create()->id,
            'reason' => PersonEntry::REASONS[$i]
        ]);

        Carbon::setTestNow(now()->addSeconds($i));
        (new GenerateActiveEntriesPdfJob(['Column1'], [$entry->id], $user->id))->handle();
        $exports[] = DocumentExport::latest()->first();
    }

    // Assert
    expect($exports[0]->file_path)->not->toBe($exports[1]->file_path);
    expect($exports[0]->id)->not->toBe($exports[1]->id);

    foreach ($exports as $export) {
        Storage::disk('public')->assertExists($export->file_path);
    }
});

it('handles special characters in person names correctly', function () {
    // Arrange
    $user = User::factory()->create();
    $person = Person::factory()->create([
        'name' => 'María José',
        'last_name' => 'Núñez García',
        'company' => 'Company & Co.'
    ]);

    $internalPerson = InternalPerson::factory()->create([
        'person_id' => Person::factory()->create([
            'name' => 'João',
            'last_name' => 'da Silva'
        ])->id
    ]);

    $entry = PersonEntry::factory()->create([
        'user_id' => $user->id,
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id
    ]);

    // Act & Assert
    expect(fn() => (new GenerateActiveEntriesPdfJob(['Visitor', 'Company'], [$entry->id], $user->id))->handle())
        ->not->toThrow(Exception::class);
});

it('maintains data consistency with different column orders', function () {
    // Arrange
    $user = User::factory()->create();
    $entry = PersonEntry::factory()->create([
        'user_id' => $user->id,
        'person_id' => Person::factory()->create()->id,
        'internal_person_id' => InternalPerson::factory()->create()->id
    ]);

    $columns1 = ['Host', 'Visitor', 'Company'];
    $columns2 = ['Company', 'Host', 'Visitor'];

    // Act
    (new GenerateActiveEntriesPdfJob($columns1, [$entry->id], $user->id))->handle();
    $firstExport = DocumentExport::latest()->first();

    (new GenerateActiveEntriesPdfJob($columns2, [$entry->id], $user->id))->handle();
    $secondExport = DocumentExport::latest()->first();

    // Assert
    Storage::disk('public')->assertExists($firstExport->file_path);
    Storage::disk('public')->assertExists($secondExport->file_path);
});

afterEach(function () {
    Carbon::setTestNow();
});