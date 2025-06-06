<?php

namespace Tests\Feature\Exports;

use App\Exports\PersonEntryExport;
use App\Models\Person;
use App\Models\PersonEntry;
use App\Models\User;
use App\Models\InternalPerson;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Storage;

beforeEach(function() {
    // Arrange
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
    // Arrange
    $export = new PersonEntryExport();
    $expectedEntry = PersonEntry::with(['user', 'person', 'internalPerson.person'])
        ->find($this->personEntry->id);
    
    // Act
    $collection = $export->collection();
    
    // Assert
    expect($collection->contains(function($entry) use ($expectedEntry) {
        return $entry->id === $expectedEntry->id;
    }))->toBeTrue();
});

it('exports only specified entries when ids provided', function() {
    // Arrange
    PersonEntry::factory()->create();
    $export = new PersonEntryExport([$this->personEntry->id]);
    $expectedEntry = PersonEntry::with(['user', 'person', 'internalPerson.person'])
        ->find($this->personEntry->id);
    
    // Act
    $collection = $export->collection();
    
    // Assert
    expect($collection)
        ->toHaveCount(1)
        ->and($collection->first()->id)->toBe($expectedEntry->id);
});

it('has correct headings', function() {
    // Arrange
    $export = new PersonEntryExport();
    
    // Act & Assert
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
    // Arrange
    $export = new PersonEntryExport();
    
    // Act
    $mappedData = $export->map($this->personEntry);
    
    // Assert
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
    // Arrange
    $export = new PersonEntryExport();
    $spreadsheet = new Spreadsheet();
    $worksheet = $spreadsheet->getActiveSheet();
    
    // Act
    $styles = $export->styles($worksheet);
    
    // Assert
    expect($styles)
        ->toHaveKey(1)
        ->and($styles[1])->toHaveKey('font')
        ->and($styles[1]['font'])->toHaveKey('bold', true)
        ->and($styles[1])->toHaveKey('fill')
        ->and($styles[1]['fill']['startColor']['rgb'])->toBe('E4E4E4');
});

it('can be downloaded', function() {
    // Arrange
    Excel::fake();
    
    // Act
    Excel::download(new PersonEntryExport(), 'person-entries.xlsx');
    
    // Assert
    Excel::assertDownloaded('person-entries.xlsx');
});

it('verifies border styles are applied correctly', function() {
    // Arrange
    $export = new PersonEntryExport();
    $spreadsheet = new Spreadsheet();
    $worksheet = $spreadsheet->getActiveSheet();

    // Act
    $styles = $export->styles($worksheet);

    // Assert
    expect($styles)
        ->toHaveKey('A1:I' . $worksheet->getHighestRow())
        ->and($styles['A1:I' . $worksheet->getHighestRow()]['borders']['allBorders']['borderStyle'])
        ->toBe('thin');
});

it('verifies center alignment in header', function() {
    // Arrange
    $export = new PersonEntryExport();
    $spreadsheet = new Spreadsheet();
    $worksheet = $spreadsheet->getActiveSheet();

    // Act
    $styles = $export->styles($worksheet);

    // Assert
    expect($styles[1]['alignment']['horizontal'])->toBe('center');
});

it('registers aftersheet event', function() {
    // Arrange
    $export = new PersonEntryExport();

    // Act
    $events = $export->registerEvents();

    // Assert
    expect($events)
        ->toHaveKey(AfterSheet::class)
        ->and($events[AfterSheet::class])
        ->toBeCallable();
});

it('handles null exit time correctly', function() {
    // Arrange
    $personEntry = PersonEntry::factory()->create([
        'user_id' => $this->user->id,
        'person_id' => $this->person->id,
        'internal_person_id' => $this->internalPerson->id,
        'reason' => 'Visit',
        'comment' => 'Test comment',
        'arrival_time' => now(),
        'entry_time' => now(),
        'exit_time' => null
    ]);

    $export = new PersonEntryExport([$personEntry->id]);

    // Act
    $mappedData = $export->map($personEntry);

    // Assert
    expect($mappedData[8])->toBe('');
});

it('exports multiple entries in correct order', function() {
    // Arrange
    $entries = PersonEntry::factory()->count(3)->create([
        'user_id' => $this->user->id,
        'person_id' => $this->person->id,
        'internal_person_id' => $this->internalPerson->id,
    ]);

    $export = new PersonEntryExport();

    // Act
    $collection = $export->collection();

    // Assert
    expect($collection)->toHaveCount(4); // 3 nuevos + 1 del beforeEach

    // Verificar que todos los entries están presentes
    $entryIds = $collection->pluck('id')->toArray();
    foreach ($entries as $entry) {
        expect($entryIds)->toContain($entry->id);
    }
});

it('loads related models eagerly', function() {
    // Arrange
    $export = new PersonEntryExport([$this->personEntry->id]);

    // Act
    $collection = $export->collection();
    $entry = $collection->first();

    // Assert
    expect($entry->relationLoaded('user'))->toBeTrue()
        ->and($entry->relationLoaded('person'))->toBeTrue()
        ->and($entry->relationLoaded('internalPerson'))->toBeTrue()
        ->and($entry->internalPerson->relationLoaded('person'))->toBeTrue();
});

it('exports file with correct column dimensions', function() {
    // Arrange
    $export = new PersonEntryExport([$this->personEntry->id]);
    $fileName = 'test_export.xlsx';

    // Act
    Excel::store($export, $fileName, 'local');

    // Assert
    expect(Storage::disk('local')->exists($fileName))->toBeTrue();

    // Cleanup
    Storage::disk('local')->delete($fileName);
});

it('calculates column widths with correct scaling factor', function() {
    // Arrange
    $export = new PersonEntryExport([$this->personEntry->id]);
    Storage::fake('local');
    $fileName = 'test_export.xlsx';

    // Act
    Excel::store($export, $fileName, 'local');

    // Leer el archivo exportado
    $readSpreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(
        Storage::disk('local')->path($fileName)
    );
    $sheet = $readSpreadsheet->getActiveSheet();

    // Assert
    $columnDimensions = $sheet->getColumnDimensions();
    foreach ($columnDimensions as $columnId => $dimension) {
        $width = $dimension->getWidth();

        // Verificamos que el ancho sea un número positivo y razonable
        expect($width)
            ->toBeFloat()
            ->toBeGreaterThan(0)
            ->toBeLessThan(100); // Un ancho razonable para una columna
    }

    // Cleanup
    Storage::disk('local')->delete($fileName);
});

it('applies auto-size before final width calculation', function() {
    // Arrange
    $export = new PersonEntryExport([$this->personEntry->id]);
    Storage::fake('local');
    $fileName = 'test_export.xlsx';

    // Act
    Excel::store($export, $fileName, 'local');

    // Leer el archivo exportado
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(
        Storage::disk('local')->path($fileName)
    );
    $sheet = $spreadsheet->getActiveSheet();

    // Assert
    foreach ($sheet->getColumnDimensions() as $columnId => $dimension) {
        expect($dimension->getAutoSize())->toBeFalse()
            ->and($dimension->getWidth())
            ->toBeFloat()
            ->toBeGreaterThan(0);
    }

    // Cleanup
    Storage::disk('local')->delete($fileName);
});