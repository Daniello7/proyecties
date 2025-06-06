<?php

namespace App\Exports;

use App\Models\PersonEntry;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PersonEntryExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents
{
    private Collection $personEntries;

    public function __construct(?array $ids = null)
    {
        $this->personEntries = $ids
            ? PersonEntry::with(['user', 'person', 'internalPerson.person'])->whereIn('id', $ids)->get()
            : PersonEntry::with(['user', 'person', 'internalPerson.person'])->get();
    }

    public function collection(): Collection
    {
        return $this->personEntries;
    }

    public function headings(): array
    {
        return [
            'ID',
            __('Porter'),
            __('Name'),
            __('Contact'),
            __('Reason'),
            __('Comment'),
            __('Arrival Time'),
            __('Entry Time'),
            __('Exit Time')
        ];
    }

    /**
     * @var PersonEntry $row
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->user->name,
            $row->person->name . ' ' . $row->person->last_name,
            $row->internalPerson->person->name . ' ' . $row->internalPerson->person->last_name,
            $row->reason,
            $row->comment,
            Carbon::parse($row->arrival_time)->format('d/m/Y H:i'),
            Carbon::parse($row->entry_time)->format('d/m/Y H:i'),
            $row->exit_time ? Carbon::parse($row->exit_time)->format('d/m/Y H:i') : ''
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => [
                        'rgb' => 'E4E4E4',
                    ]
                ],
                'alignment' => [
                    'horizontal' => 'center'
                ]
            ],

            'A1:I' . $sheet->getHighestRow() => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                    ],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                foreach ($event->sheet->getColumnIterator() as $column) {
                    $event->sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
                }

                $event->sheet->calculateColumnWidths();

                foreach ($event->sheet->getColumnIterator() as $column) {
                    $columnLetter = $column->getColumnIndex();
                    $width = $event->sheet->getColumnDimension($columnLetter)->getWidth();
                    $event->sheet->getColumnDimension($columnLetter)->setAutoSize(false);
                    $event->sheet->getColumnDimension($columnLetter)->setWidth($width * 0.80);
                }
            },
        ];
    }

}