<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Models\Store;
use Carbon\Carbon;

abstract class BaseExport implements WithEvents, WithCustomStartCell, WithStyles, WithColumnWidths, WithHeadings
{
    protected $sellerId;
    protected $startDate;
    protected $endDate;
    protected $storeName;
    protected $reportTitle;
    protected $columnCount;

    public function __construct($sellerId, $startDate, $endDate)
    {
        $this->sellerId = $sellerId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        
        // Get store name from database
        $store = Store::where('user_id', $sellerId)->first();
        $this->storeName = $store ? $store->name : 'Toko';
    }

    /**
     * @return array
     */
    abstract public function headings(): array;

    /**
     * @return string
     */
    public function startCell(): string
    {
        return 'A8';
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        // Default column widths, can be overridden in child classes
        $widths = [];
        for ($i = 'A'; $i <= 'Z'; $i++) {
            $widths[$i] = 18;
        }
        return $widths;
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Apply styles to the header row
        $headerRowStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'], // Indigo color
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
        ];

        // Get the last row of data
        $lastRow = $sheet->getHighestRow();
        
        // Apply styles to the data rows
        $dataRowsStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        return [
            8 => $headerRowStyle,
            'A9:' . $sheet->getHighestColumn() . $lastRow => $dataRowsStyle,
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $this->columnCount = count($this->headings());
                $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($this->columnCount);
                
                // Set page orientation and paper size
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                
                // Add header with logo and title
                $sheet->mergeCells('A1:' . $lastColumn . '1');
                $sheet->setCellValue('A1', 'TeknoShop');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(18)->setColor(new Color('4F46E5'));
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Store name
                $sheet->mergeCells('A2:' . $lastColumn . '2');
                $sheet->setCellValue('A2', $this->storeName);
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Report title
                $sheet->mergeCells('A3:' . $lastColumn . '3');
                $sheet->setCellValue('A3', $this->reportTitle);
                $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Date range
                $sheet->mergeCells('A4:' . $lastColumn . '4');
                $sheet->setCellValue('A4', 'Periode: ' . Carbon::parse($this->startDate)->format('d M Y') . ' - ' . Carbon::parse($this->endDate)->format('d M Y'));
                $sheet->getStyle('A4')->getFont()->setSize(12);
                $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Add some space
                $sheet->mergeCells('A5:' . $lastColumn . '5');
                $sheet->setCellValue('A5', '');
                
                // Format the header row
                $sheet->getRowDimension(8)->setRowHeight(25);
                
                // Add footer
                $lastRow = $sheet->getHighestRow() + 2;
                $sheet->mergeCells('A' . $lastRow . ':' . $lastColumn . $lastRow);
                $sheet->setCellValue('A' . $lastRow, 'Laporan dibuat pada: ' . Carbon::now()->format('d M Y H:i'));
                $sheet->getStyle('A' . $lastRow)->getFont()->setItalic(true);
                
                // Auto-filter for the header row
                $sheet->setAutoFilter('A8:' . $lastColumn . '8');
                
                // Set row height for data rows
                for ($i = 9; $i <= $sheet->getHighestRow(); $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(20);
                }
                
                // Apply zebra striping to data rows
                for ($i = 9; $i <= $sheet->getHighestRow(); $i++) {
                    if ($i % 2 == 0) {
                        $sheet->getStyle('A' . $i . ':' . $lastColumn . $i)->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->setStartColor(new Color('F3F4F6'));
                    }
                }
                
                // Apply specific formatting for currency columns
                $this->applyCurrencyFormatting($sheet);
            },
        ];
    }
    
    /**
     * Apply currency formatting to specific columns
     * This method should be overridden in child classes
     */
    protected function applyCurrencyFormatting($sheet)
    {
        // To be implemented in child classes
    }
}
