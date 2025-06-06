<?php

namespace Tests\Feature\Livewire;

use App\Livewire\DocumentExportList;
use App\Models\DocumentExport;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use function Pest\Livewire\livewire;

beforeEach(function () {
    Storage::fake('public');
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('mounts with correct initial configuration', function () {
    livewire(DocumentExportList::class)
        ->assertSet('type', 'pdf')
        ->assertSet('documentExports', new \Illuminate\Database\Eloquent\Collection());
});

it('can change document type', function () {
    livewire(DocumentExportList::class)
        ->call('changeType', 'xlsx')
        ->assertSet('type', 'xlsx');
});

it('shows only user documents of selected type', function () {
    // Arrange
    $pdfDocument = DocumentExport::factory()
        ->pdf()
        ->for($this->user)
        ->create();

    $xlsxDocument = DocumentExport::factory()
        ->xlsx()
        ->for($this->user)
        ->create();

    $otherUserDocument = DocumentExport::factory()
        ->pdf()
        ->create();

    // Assert PDF documents
    livewire(DocumentExportList::class)
        ->assertSet('type', 'pdf')
        ->assertSee($pdfDocument->filename)
        ->assertDontSee($xlsxDocument->filename)
        ->assertDontSee($otherUserDocument->filename);

    // Assert XLSX documents
    livewire(DocumentExportList::class)
        ->set('type', 'xlsx')
        ->assertSee($xlsxDocument->filename)
        ->assertDontSee($pdfDocument->filename)
        ->assertDontSee($otherUserDocument->filename);
});

it('can update document viewed status', function () {
    // Arrange
    $document = DocumentExport::factory()
        ->pdf()
        ->unviewed()
        ->for($this->user)
        ->create();

    // Act & Assert
    livewire(DocumentExportList::class)
        ->call('updateViewedAt', $document->id)
        ->assertDispatched('updated-document')
        ->assertDispatched('open-document');

    $this->assertNotNull($document->fresh()->viewed_at);
});

it('can toggle document viewed status', function () {
    // Arrange
    $document = DocumentExport::factory()
        ->pdf()
        ->unviewed()
        ->for($this->user)
        ->create();

    // Act & Assert - Mark as viewed
    livewire(DocumentExportList::class)
        ->call('changeViewedAt', $document->id)
        ->assertDispatched('updated-document');

    $this->assertNotNull($document->fresh()->viewed_at);

    // Act & Assert - Mark as not viewed
    livewire(DocumentExportList::class)
        ->call('changeViewedAt', $document->id)
        ->assertDispatched('updated-document');

    $this->assertNull($document->fresh()->viewed_at);
});

it('can mark all documents as viewed', function () {
    // Arrange
    $documents = DocumentExport::factory(3)
        ->pdf()
        ->unviewed()
        ->for($this->user)
        ->create();

    // Act
    livewire(DocumentExportList::class)
        ->call('viewedAll')
        ->assertDispatched('updated-document');

    // Assert
    foreach ($documents as $document) {
        $this->assertNotNull($document->fresh()->viewed_at);
    }
});

it('can mark all documents as not viewed', function () {
    // Arrange
    $documents = DocumentExport::factory(3)
        ->pdf()
        ->viewed()
        ->for($this->user)
        ->create();

    // Act
    livewire(DocumentExportList::class)
        ->call('notViewedAll')
        ->assertDispatched('updated-document');

    // Assert
    foreach ($documents as $document) {
        $this->assertNull($document->fresh()->viewed_at);
    }
});

it('can delete document', function () {
    // Arrange
    $file = UploadedFile::fake()->create('document.pdf');
    Storage::disk('public')->put('documents/test.pdf', $file->getContent());

    $document = DocumentExport::factory()
        ->pdf()
        ->for($this->user)
        ->create([
            'file_path' => 'documents/test.pdf'
        ]);

    // Act & Assert
    livewire(DocumentExportList::class)
        ->call('deletePdf', $document->id)
        ->assertDispatched('updated-document');

    $this->assertDatabaseMissing('document_exports', ['id' => $document->id]);
    Storage::disk('public')->assertMissing('documents/test.pdf');
});

it('downloads excel file when updating viewed status', function () {
    // Arrange
    $file = UploadedFile::fake()->create('document.xlsx');
    Storage::disk('public')->put('documents/test.xlsx', $file->getContent());

    $document = DocumentExport::factory()
        ->xlsx()
        ->for($this->user)
        ->create([
            'file_path' => 'documents/test.xlsx',
            'filename' => 'test'
        ]);

    // Act & Assert
    $component = livewire(DocumentExportList::class);

    $component->call('updateViewedAt', $document->id);

    expect($document->fresh()->viewed_at)->not->toBeNull();

    $component->assertDispatched('updated-document');

    Storage::disk('public')->assertExists('documents/test.xlsx');

    expect($component->get('type'))->toBe('xlsx');
});