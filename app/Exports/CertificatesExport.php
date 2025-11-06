<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Carbon\Carbon;

class CertificatesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function collection()
    {
        return $this->items;
    }

    public function headings(): array
    {
        return [
            'Vārds',
            'E-pasts',
            'Modulis',
            'Progress (%)',
            'Progress (uzdevumi)',
            'Sertifikāts',
            'Sertifikāta datums',
            'Pirmā aktivitāte',
            'Pēdējā aktivitāte',
        ];
    }

    public function map($item): array
    {
        return [
            $item->name,
            $item->email,
            $item->course_title_lv,
            round($item->percentage ?? 0, 2),
            ($item->completed_items ?? $item->current_order ?? 0) . ' no ' . ($item->total_items ?? 0),
            $item->certificate_date ? 'Jā' : 'Nē',
            $item->certificate_date ? Carbon::parse($item->certificate_date)->format('d/m/Y H:i') : '-',
            Carbon::parse($item->created_at)->format('d/m/Y H:i'),
            Carbon::parse($item->updated_at)->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '9810FA']
                ]
            ],
        ];
    }
}

