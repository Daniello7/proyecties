<?php

namespace Tests\Feature\Jobs;

use App\Exports\PersonEntryExport;
use App\Jobs\GenerateActiveEntriesExcelJob;
use App\Models\DocumentExport;
use App\Models\PersonEntry;
use App\Models\User;
use App\Models\Person;
use App\Models\InternalPerson;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Facades\Excel;

beforeEach(function () {
    Excel::fake();
});

it('creates document export and stores excel file', function () {
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
    $expectedFilename = 'Active Entries';

    // Act
    (new GenerateActiveEntriesExcelJob($entries_id, $user->id))->handle();

    // Assert
    $documentExport = DocumentExport::first();

    expect($documentExport)
        ->user_id->toBe($user->id)
        ->filename->toBe($expectedFilename)
        ->type->toBe('xlsx');

    expect($documentExport->file_path)
        ->toMatch('/^excel\/exports\/\d{4}-\d{2}\/user_' . $user->id . '_active-entries_[a-f0-9-]+\.xlsx$/');

    Excel::assertStored(
        $documentExport->file_path,
        'public',
        function (PersonEntryExport $export) use ($entries_id) {
            $exportEntryIds = $export->collection()->pluck('id')->toArray();
            return $exportEntryIds === $entries_id;
        }
    );

    expect(session()->get('success'))->toBe(__('messages.document_created'));
});

it('can be dispatched with correct parameters', function () {
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

    // Act & Assert
    expect(fn() => GenerateActiveEntriesExcelJob::dispatch($entries_id, $user->id))
        ->not()->toThrow(Exception::class);

    $job = new GenerateActiveEntriesExcelJob($entries_id, $user->id);
    expect($job)
        ->entries_id->toBe($entries_id)
        ->user_id->toBe($user->id);
});

it('handles empty entries array correctly', function () {
    // Arrange
    $user = User::factory()->create();
    $entries_id = [];

    // Act & Assert
    expect(fn() => (new GenerateActiveEntriesExcelJob($entries_id, $user->id))->handle())
        ->not->toThrow(Exception::class);

    $documentExport = DocumentExport::first();
    expect($documentExport)->not->toBeNull();
});

it('fails when user does not exist', function () {
    // Arrange
    $nonExistentUserId = (User::max('id') ?? 1) + 1;
    Person::factory()->create();
    InternalPerson::factory()->create([
        'person_id' => Person::factory()->create()->id
    ]);

    $entries = PersonEntry::factory(3)->create([
        'reason' => PersonEntry::REASONS[0],
    ]);
    $entries_id = $entries->pluck('id')->toArray();

    // Act & Assert
    expect(fn() => (new GenerateActiveEntriesExcelJob($entries_id, $nonExistentUserId))->handle())
        ->toThrow(QueryException::class, 'FOREIGN KEY constraint failed');
});

it('creates file with correct content structure', function () {
    // Arrange
    $user = User::factory()->create();
    $person = Person::factory()->create([
        'name' => 'John',
        'last_name' => 'Doe'
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
        'comment' => 'Important meeting',
        'arrival_time' => '2025-06-04 10:00:00',
        'entry_time' => '2025-06-04 10:15:00',
        'exit_time' => '2025-06-04 11:00:00'
    ]);

    // Act
    (new GenerateActiveEntriesExcelJob([$entries->id], $user->id))->handle();

    // Assert
    Excel::assertStored(
        DocumentExport::first()->file_path,
        'public',
        function (PersonEntryExport $export) use ($user, $person) {
            $rows = $export->collection();
            $firstRow = $rows->first();

            return $firstRow->user->name === $user->name &&
                $firstRow->person->name === 'John' &&
                $firstRow->person->last_name === 'Doe' &&
                $firstRow->internalPerson->person->name === 'Jane' &&
                $firstRow->internalPerson->person->last_name === 'Smith' &&
                $firstRow->reason === PersonEntry::REASONS[0] &&
                $firstRow->comment === 'Important meeting';
        }
    );
});

it('creates correct file path structure with date', function () {
    // Arrange
    $user = User::factory()->create();
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create([
        'person_id' => Person::factory()->create()->id
    ]);

    $entries = PersonEntry::factory()->create([
        'user_id' => $user->id,
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'reason' => PersonEntry::REASONS[0],
    ]);

    $currentMonth = now()->format('Y-m');

    // Act
    (new GenerateActiveEntriesExcelJob([$entries->id], $user->id))->handle();

    // Assert
    $documentExport = DocumentExport::first();
    expect($documentExport->file_path)
        ->toContain("excel/exports/{$currentMonth}")
        ->toContain("user_{$user->id}_")
        ->toContain('.xlsx');
});