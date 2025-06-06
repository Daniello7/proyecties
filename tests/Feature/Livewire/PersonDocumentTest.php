<?php

namespace Tests\Feature\Livewire;

use App\Livewire\PersonDocument;
use App\Models\Person;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    $this->person = Person::factory()->create();
});

it('toggles documents visibility', function () {
    $component = Livewire::test(PersonDocument::class, ['person' => $this->person]);

    expect($component->showDocuments)->toBeFalse();

    $component->call('updateShowDocuments');
    expect($component->showDocuments)->toBeTrue();

    $component->call('updateShowDocuments');
    expect($component->showDocuments)->toBeFalse();
});

it('shows flash message when file is uploaded', function () {
    Livewire::test(PersonDocument::class, ['person' => $this->person])
        ->call('uploadedFile')
        ->assertSee(__('messages.document_created'));
});

it('deletes document and shows success message', function () {
    Storage::fake('public');

    $document = $this->person->documents()->create([
        'filename' => 'Rules 2025-06-06.pdf',
        'original_name' => 'test.pdf',
        'type' => 'application/pdf',
        'size' => 1024,
        'path' => 'pdf/person-documents/2025-06/rules-person-1.pdf'
    ]);

    Storage::disk('public')->put($document->path, 'test content');

    Livewire::test(PersonDocument::class, ['person' => $this->person])
        ->call('deleteDocument', $document->id)
        ->assertSee(__('messages.document_deleted'));

    Storage::disk('public')->assertMissing($document->path);
    $this->assertDatabaseMissing('person_documents', ['id' => $document->id]);
});

it('throws exception when trying to delete non-existent document', function () {
    $component = Livewire::test(PersonDocument::class, ['person' => $this->person]);

    expect(fn() => $component->call('deleteDocument', 9999999))
        ->toThrow(\ErrorException::class);
});

it('loads documents ordered by creation date', function () {
    $oldDocument = $this->person->documents()->create([
        'filename' => 'Rules 2025-06-04.pdf',
        'original_name' => 'old.pdf',
        'type' => 'application/pdf',
        'size' => 1024,
        'path' => 'pdf/person-documents/2025-06/rules-person-1-old.pdf',
        'created_at' => now()->subDays(2)
    ]);

    $newDocument = $this->person->documents()->create([
        'filename' => 'Rules 2025-06-06.pdf',
        'original_name' => 'new.pdf',
        'type' => 'application/pdf',
        'size' => 1024,
        'path' => 'pdf/person-documents/2025-06/rules-person-1-new.pdf',
        'created_at' => now()
    ]);

    $component = Livewire::test(PersonDocument::class, ['person' => $this->person])
        ->call('updateShowDocuments');

    $component->assertSeeInOrder([
        $oldDocument->filename,
        $newDocument->filename,
    ]);
});

it('renders component with person documents', function () {
    $document = $this->person->documents()->create([
        'filename' => 'Rules 2025-06-06.pdf',
        'original_name' => 'test.pdf',
        'type' => 'application/pdf',
        'size' => 1024,
        'path' => 'pdf/person-documents/2025-06/rules-person-1.pdf'
    ]);

    Livewire::test(PersonDocument::class, ['person' => $this->person])
        ->assertSeeText(__('Commitments issued'))
        ->assertSeeText('Rules 2025-06-06.pdf');
});

it('hides documents section by default', function () {
    Livewire::test(PersonDocument::class, ['person' => $this->person])
        ->assertSet('showDocuments', false)
        ->assertSeeHtml('class="hidden');
});

it('shows documents section after toggle', function () {
    Livewire::test(PersonDocument::class, ['person' => $this->person])
        ->call('updateShowDocuments')
        ->assertSet('showDocuments', true)
        ->assertDontSeeHtml('class="hidden');
});

it('shows document download link with correct storage url', function () {
    $document = $this->person->documents()->create([
        'filename' => 'Rules 2025-06-06.pdf',
        'original_name' => 'test.pdf',
        'type' => 'application/pdf',
        'size' => 1024,
        'path' => 'pdf/person-documents/2025-06/rules-person-1.pdf'
    ]);

    Livewire::test(PersonDocument::class, ['person' => $this->person])
        ->assertSeeHtml('href="' . Storage::url($document->path) . '"')
        ->assertSeeHtml('target="_blank"');
});