<?php

namespace Tests\Feature\Exports;

use App\Exports\PersonEntryExport;
use App\Models\Person;
use App\Models\PersonEntry;
use App\Models\User;
use App\Models\InternalPerson;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

beforeEach(function() {
    // Crear datos necesarios
    $this->user = User::factory()->create(['name' => 'Portero Test']);
    
    $this->person = Person::factory()->create([
        'name' => 'Visitante',
        'last_name' => 'Prueba'
    ]);
    
    $this->contactPerson = Person::factory()->create([
        'name' => 'Contacto',
        'last_name' => 'Interno'
    ]);
    
    $this->internalPerson = InternalPerson::factory()->create([
        'person_id' => $this->contactPerson->id
    ]);
    
    $this->personEntry = PersonEntry::factory()->create([
        'user_id' => $this->user->id,
        'person_id' => $this->person->id,
        'internal_person_id' => $this->internalPerson->id,
        'reason' => 'Visit',
        'comment' => 'Comentario de prueba',
        'arrival_time' => '2025-01-01 09:00:00',
        'entry_time' => '2025-01-01 09:15:00',
        'exit_time' => '2025-01-01 10:00:00'
    ]);
});

it('exports all entries when no ids provided', function() {
    $export = new PersonEntryExport();
    
    // Obtener la entrada con las relaciones cargadas para comparar
    $expectedEntry = PersonEntry::with(['user', 'person', 'internalPerson.person'])
        ->find($this->personEntry->id);
    
    $collection = $export->collection();
    
    expect($collection->contains(function($entry) use ($expectedEntry) {
        return $entry->id === $expectedEntry->id;
    }))->toBeTrue();
});

it('exports only specified entries when ids provided', function() {
    $otherEntry = PersonEntry::factory()->create();
    $export = new PersonEntryExport([$this->personEntry->id]);
    
    $expectedEntry = PersonEntry::with(['user', 'person', 'internalPerson.person'])
        ->find($this->personEntry->id);
    
    $collection = $export->collection();
    
    expect($collection)
        ->toHaveCount(1)
        ->and($collection->first()->id)->toBe($expectedEntry->id);
});

it('has correct headings', function() {
    $export = new PersonEntryExport();
    
    expect($export->headings())->toBe([
        'ID',
        __('Porter'),
        __('Name'),
        __('Contact'),
        __('Reason'),
        __('Comment'),
        __('Arrival Time'),
        __('Entry Time'),
        __('Exit Time')
    ]);
});

it('maps entry data correctly', function() {
    $export = new PersonEntryExport();
    $mappedData = $export->map($this->personEntry);
    
    expect($mappedData)->toBe([
        $this->personEntry->id,
        'Portero Test',
        'Visitante Prueba',
        'Contacto Interno',
        'Visit',
        'Comentario de prueba',
        '01/01/2025 09:00',
        '01/01/2025 09:15',
        '01/01/2025 10:00'
    ]);
});

it('applies correct styles', function() {
    $export = new PersonEntryExport();
    $spreadsheet = new Spreadsheet();
    $worksheet = $spreadsheet->getActiveSheet();
    
    $styles = $export->styles($worksheet);
    
    expect($styles)
        ->toHaveKey(1)
        ->and($styles[1])->toHaveKey('font')
        ->and($styles[1]['font'])->toHaveKey('bold', true)
        ->and($styles[1])->toHaveKey('fill')
        ->and($styles[1]['fill']['startColor']['rgb'])->toBe('E4E4E4');
});

it('can be downloaded', function() {
    Excel::fake();
    Excel::download(new PersonEntryExport(), 'person-entries.xlsx');
    Excel::assertDownloaded('person-entries.xlsx');
});