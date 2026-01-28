<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class DriverReportExport implements FromArray, WithStyles, WithColumnWidths
{
    protected $reportData;
    protected $startDate;
    protected $endDate;

    public function __construct($reportData, $startDate, $endDate)
    {
        $this->reportData = $reportData;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Return data array
     */
    public function array(): array
    {
        $startDateFormatted = Carbon::createFromFormat('Y-m-d', $this->startDate)->format('d M Y');
        $endDateFormatted = Carbon::createFromFormat('Y-m-d', $this->endDate)->format('d M Y');
        
        $data = [];
        
        // Row 1: Company Header
        $data[] = ['SISTEM MANAJEMEN MESS PENGEMUDI'];
        
        // Row 2: Report Title
        $data[] = ['LAPORAN CHECKOUT PENGEMUDI'];
        
        // Row 3: Period
        $data[] = ['Periode: ' . $startDateFormatted . ' s/d ' . $endDateFormatted];
        
        // Row 4: Created Date
        $data[] = ['Dibuat: ' . Carbon::now()->format('d M Y H:i:s')];
        
        // Row 5: Empty (add actual column count)
        $data[] = ['', '', '', '', '', '', ''];
        
        // Row 6: Table Header
        $data[] = [
            'No',
            'Nama Driver',
            'ID Card',
            'Kamar',
            'Locker',
            'Total (Rp)',
            'Pelanggaran'
        ];
        
        // Data Rows (starting from Row 7)
        $rowNumber = 1;
        foreach ($this->reportData as $item) {
            $data[] = [
                $rowNumber,
                $item['name'],
                $item['id_card'],
                $item['room_usages'],
                $item['locker_usages'],
                $item['total_nominal'],
                $item['violation_count']
            ];
            $rowNumber++;
        }
        
        // Total Nominal Row
        $data[] = [
            '',
            '',
            'TOTAL NOMINAL',
            '',
            '',
            $this->reportData->sum('total_nominal'),
            ''
        ];
        
        // Empty row for spacing
        $data[] = ['', '', '', '', '', '', ''];
        
        // Summary Title
        $data[] = ['RINGKASAN LAPORAN', '', '', '', '', '', ''];
        
        // Summary Header
        $data[] = ['Keterangan', '', 'Nilai', '', '', '', ''];
        
        // Summary Data
        $data[] = ['Total Driver', '', count($this->reportData), '', '', '', ''];
        $data[] = ['Total Kamar', '', $this->reportData->sum('room_usages'), '', '', '', ''];
        $data[] = ['Total Locker', '', $this->reportData->sum('locker_usages'), '', '', '', ''];
        $data[] = ['Total Nominal (Rp)', '', $this->reportData->sum('total_nominal'), '', '', '', ''];
        $data[] = ['Total Pelanggaran', '', $this->reportData->sum('violation_count'), '', '', '', ''];
        
        return $data;
    }

    /**
     * Define column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 22,
            'C' => 13,
            'D' => 10,
            'E' => 10,
            'F' => 15,
            'G' => 12,
        ];
    }

    /**
     * Apply professional styling to match Excel template
     */
    public function styles($sheet)
    {
        $dataCount = count($this->reportData);
        
        // Row positions calculation
        $dataStartRow = 7;                      // Data mulai dari row 7
        $lastDataRow = $dataStartRow - 1 + $dataCount;  // Row terakhir data
        $totalNominalRow = $lastDataRow + 1;    // Row untuk TOTAL NOMINAL
        $emptyRow = $totalNominalRow + 1;       // Empty row
        $summaryTitleRow = $emptyRow + 1;       // RINGKASAN LAPORAN
        $summaryHeaderRow = $summaryTitleRow + 1;
        $summaryDataStart = $summaryHeaderRow + 1;
        
        // ===== ROW 1: COMPANY HEADER =====
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '000000']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFD966']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension('1')->setRowHeight(28);

        // ===== ROW 2: REPORT TITLE =====
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '000000']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFD966']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension('2')->setRowHeight(24);

        // ===== ROW 3: PERIOD =====
        $sheet->mergeCells('A3:G3');
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['size' => 10, 'italic' => true, 'color' => ['rgb' => '404040']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension('3')->setRowHeight(18);

        // ===== ROW 4: CREATED DATE =====
        $sheet->mergeCells('A4:G4');
        $sheet->getStyle('A4')->applyFromArray([
            'font' => ['size' => 9, 'color' => ['rgb' => '666666']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension('4')->setRowHeight(16);

        // ===== ROW 5: EMPTY SPACING =====
        for ($col = 0; $col < 7; $col++) {
            $colLetter = chr(65 + $col);
            $sheet->getStyle($colLetter . '5')->applyFromArray([
            ]);
        }
        $sheet->getRowDimension('5')->setRowHeight(8);

        // ===== ROW 6: TABLE HEADER =====
        for ($col = 0; $col < 7; $col++) {
            $colLetter = chr(65 + $col);
            $sheet->getStyle($colLetter . '6')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => '000000'], 'size' => 10],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFD966']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ]);
        }
        $sheet->getRowDimension('6')->setRowHeight(22);

        // ===== DATA ROWS =====
        for ($row = $dataStartRow; $row <= $lastDataRow; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(18);
            
            for ($col = 0; $col < 7; $col++) {
                $colLetter = chr(65 + $col);
                $cellRef = $colLetter . $row;
                
                // Alternating background
                $bgColor = ($row % 2 == 0) ? 'FFFFFF' : 'F2F2F2';
                
                $sheet->getStyle($cellRef)->applyFromArray([
                    'font' => ['size' => 9],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);
                
                // Column-specific alignment
                if (in_array($colLetter, ['A', 'D', 'E', 'G'])) {
                    $sheet->getStyle($cellRef)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                } elseif ($colLetter === 'F') {
                    $sheet->getStyle($cellRef)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                } else {
                    $sheet->getStyle($cellRef)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                }
            }
            
            // Number format for column F (Total)
            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('#,##0');
        }

        // ===== TOTAL NOMINAL ROW =====
        $sheet->getRowDimension($totalNominalRow)->setRowHeight(20);
        for ($col = 0; $col < 7; $col++) {
            $colLetter = chr(65 + $col);
            $cellRef = $colLetter . $totalNominalRow;
            
            // Hanya kolom C (merged C:D) dan F yang berwarna gray
            if (in_array($colLetter, ['C', 'F'])) {
                $sheet->getStyle($cellRef)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '000000']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'A9A9A9']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                
                if ($colLetter === 'F') {
                    $sheet->getStyle($cellRef)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle($cellRef)->getNumberFormat()->setFormatCode('#,##0');
                }
            } else {
                // Kolom lain tanpa warna (termasuk D karena merged dengan C)
                $sheet->getStyle($cellRef)->applyFromArray([
                    'font' => ['size' => 9],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFFFF']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
            }
        }

        // ===== EMPTY ROW =====
        $sheet->getRowDimension($emptyRow)->setRowHeight(8);

        // ===== MERGE CELLS =====
        // Merge TOTAL NOMINAL row kolom C & D
        $sheet->mergeCells('C' . $totalNominalRow . ':D' . $totalNominalRow);
        
        // Merge Summary Keterangan column (A & B) untuk semua summary rows
        $sheet->mergeCells('A' . $summaryHeaderRow . ':B' . $summaryHeaderRow);
        for ($i = 0; $i < 5; $i++) {
            $row = $summaryDataStart + $i;
            $sheet->mergeCells('A' . $row . ':B' . $row);
        }

        // ===== SUMMARY TITLE =====
        $sheet->mergeCells('A' . $summaryTitleRow . ':C' . $summaryTitleRow);
        $sheet->getStyle('A' . $summaryTitleRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '000000']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFD966']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension($summaryTitleRow)->setRowHeight(22);
        
        // Set background dan borders untuk kolom B-C di summary title
        for ($col = 1; $col < 3; $col++) {
            $colLetter = chr(65 + $col);
            $sheet->getStyle($colLetter . $summaryTitleRow)->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFD966']],
            ]);
        }
        
        // Add borders to kolom D-G di summary title
        for ($col = 3; $col < 7; $col++) {
            $colLetter = chr(65 + $col);
            $sheet->getStyle($colLetter . $summaryTitleRow)->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFFFF']],
            ]);
        }

        // ===== SUMMARY HEADER =====
        // Kolom A-B (merged)
        $sheet->getStyle('A' . $summaryHeaderRow)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '000000'], 'size' => 10],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFD966']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        // Kolom C (Nilai) - juga kuning dengan border
        $sheet->getStyle('C' . $summaryHeaderRow)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '000000'], 'size' => 10],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFD966']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        // Kolom D-G di summary header dengan border
        for ($col = 3; $col < 7; $col++) {
            $colLetter = chr(65 + $col);
            $sheet->getStyle($colLetter . $summaryHeaderRow)->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFFFF']],
            ]);
        }
        $sheet->getRowDimension($summaryHeaderRow)->setRowHeight(20);

        // ===== SUMMARY DATA =====
        for ($i = 0; $i < 5; $i++) {
            $row = $summaryDataStart + $i;
            $sheet->getRowDimension($row)->setRowHeight(18);
            
            // Background color untuk summary data
            $bgColor = ($i % 2 == 0) ? 'F2F2F2' : 'FFFFFF';
            
            // Kolom A-B (merged)
            $sheet->getStyle('A' . $row)->applyFromArray([
                'font' => ['size' => 9],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
            ]);
            
            // Kolom C (Nilai) dengan border
            $sheet->getStyle('C' . $row)->applyFromArray([
                'font' => ['size' => 9],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER],
            ]);
            
            // Format currency for total nominal row
            if ($i === 3) {
                $sheet->getStyle('C' . $row)->getNumberFormat()->setFormatCode('#,##0');
            }
            
            // Kolom D-G dengan styling
            for ($col = 3; $col < 7; $col++) {
                $colLetter = chr(65 + $col);
                $sheet->getStyle($colLetter . $row)->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFFFF']],
                ]);
            }
        }

        return [];
    }
}


