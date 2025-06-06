<?php

use App\Livewire\DropZone;
use App\Models\Person;
use Illuminate\Http\UploadedFile;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->person = Person::factory()->create();
});

it('mounts correctly with person data', function () {
    $component = livewire(DropZone::class, ['person' => $this->person])
        ->assertSet('person_id', $this->person->id)
        ->assertSet('showUploadButton', false)
        ->assertSet('dropZoneMessage', 'Drag your file here or click to select it');
});

it('validates pdf file upload correctly', function () {
    Storage::fake('public');

    $pdf = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

    livewire(DropZone::class, [
        'person' => $this->person,
        'allowedTypeFile' => 'pdf'
    ])
        ->set('dropZoneFile', $pdf)
        ->assertSet('original_name', 'document.pdf')
        ->assertSet('type', 'application/pdf')
        ->assertSet('size', $pdf->getSize())
        ->assertSet('showUploadButton', true)
        ->assertHasNoErrors();
});

it('rejects invalid file type', function () {
    $invalidFile = UploadedFile::fake()->create('document.txt', 100);

    livewire(DropZone::class, [
        'person' => $this->person,
        'allowedTypeFile' => 'pdf'
    ])
        ->set('dropZoneFile', $invalidFile)
        ->assertHasErrors(['dropZoneFile']);
});

it('rejects file larger than 10MB', function () {
    $largeFile = UploadedFile::fake()->create('document.pdf', 11000);

    livewire(DropZone::class, [
        'person' => $this->person,
        'allowedTypeFile' => 'pdf'
    ])
        ->set('dropZoneFile', $largeFile)
        ->assertHasErrors(['dropZoneFile']);
});

it('resets component state after successful upload', function () {
    Storage::fake('public');

    $pdf = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

    $component = livewire(DropZone::class, [
        'person' => $this->person,
        'allowedTypeFile' => 'pdf'
    ])
        ->set('dropZoneFile', $pdf)
        ->set('filename', 'test.pdf')
        ->set('original_name', 'document.pdf')
        ->set('type', 'application/pdf')
        ->set('size', $pdf->getSize());

    $component->call('uploadFile');

    $component
        ->assertSet('dropZoneFile', null)
        ->assertSet('showUploadButton', false)
        ->assertSet('original_name', null)
        ->assertSet('type', null)
        ->assertSet('size', null)
        ->assertSet('path', null);
});