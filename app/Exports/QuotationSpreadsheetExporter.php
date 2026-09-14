<?php

namespace App\Exports;

use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Builds a "Penawaran Docking" workbook for a quotation, mirroring the layout
 * of the original sample quotation files (letterhead, ship identity, item table).
 */
class QuotationSpreadsheetExporter
{
    private const HEADER_FILL = 'FFF9E79F';
    private const LABEL_COLUMN_WIDTH = 42;

    public static function make(Quotation $quotation): Spreadsheet
    {
        $quotation->loadMissing(['client', 'clientContact', 'ship', 'creator']);
        $items = QuotationItem::treeForQuotation($quotation->id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Penawaran');

        foreach (['A' => 6, 'B' => self::LABEL_COLUMN_WIDTH, 'C' => 14, 'D' => 10, 'E' => 18, 'F' => 20] as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        $row = 1;

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setPath(public_path('logo_caputra.png'));
        $drawing->setHeight(40);
        $drawing->setCoordinates("A{$row}");
        $drawing->setWorksheet($sheet);

        $row += 3;

        $client = $quotation->client;
        $rows = [
            ['To', $client->client_name ?: $client->client_no, 'Date', optional($quotation->quotation_date)->format('d M Y')],
            ['Attn', $quotation->clientContact?->name ?? '-', 'Contact', $quotation->creator?->name ?? '-'],
            ['Email', $quotation->clientContact?->email ?? $client->client_email, 'Email', $quotation->creator?->email ?? '-'],
            ['Address', trim(($client->company_address_line_1 ?? '').' '.($client->company_address_line_2 ?? '')) ?: '-', 'Quote no.', $quotation->quote_no],
            ['Phone / Fax', $client->client_phone_number ?? '-', 'Revision', (string) $quotation->revision],
        ];

        foreach ($rows as [$leftLabel, $leftValue, $rightLabel, $rightValue]) {
            $sheet->setCellValue("A{$row}", $leftLabel);
            $sheet->setCellValue("B{$row}", $leftValue);
            $sheet->setCellValue("D{$row}", $rightLabel);
            $sheet->setCellValue("E{$row}", $rightValue);
            $sheet->getStyle("A{$row}")->getFont()->setBold(true);
            $sheet->getStyle("D{$row}")->getFont()->setBold(true);
            $row++;
        }

        $row++;
        $sheet->setCellValue("A{$row}", 'PENAWARAN DOCKING '.$quotation->ship->ship_name);
        $sheet->mergeCells("A{$row}:F{$row}");
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row += 2;

        $sheet->setCellValue("A{$row}", 'INDENTITAS KAPAL');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;

        $ship = $quotation->ship;
        $identity = [
            ['NAMA KAPAL', $ship->ship_name, 'LEBAR', self::formatMeasurement($ship->width_value, $ship->width_unit)],
            ['LOA', self::formatMeasurement($ship->loa_value, $ship->loa_unit), 'DRAFT', self::formatMeasurement($ship->draught_value, $ship->draught_unit)],
            ['LBP', self::formatMeasurement($ship->lbp_value, $ship->lbp_unit), 'GT / NT', ($ship->gt ?? '-').' / '.($ship->nt ?? '-')],
            ['TINGGI', self::formatMeasurement($ship->height_value, $ship->height_unit), 'DAYA M/E', $ship->power_me ?? '-'],
            ['DOCKING', 'TAHUN '.$quotation->docking_year, 'JENIS SURVEY', $quotation->survey_type],
        ];

        foreach ($identity as [$leftLabel, $leftValue, $rightLabel, $rightValue]) {
            $sheet->setCellValue("A{$row}", $leftLabel);
            $sheet->setCellValue("B{$row}", $leftValue);
            $sheet->setCellValue("D{$row}", $rightLabel);
            $sheet->setCellValue("E{$row}", $rightValue);
            $row++;
        }

        $row++;
        $tableHeaderRow = $row;
        $headers = ['No.', 'URAIAN', 'QTY.', 'UNIT', 'UNIT RATE (Rp)', 'TOTAL (Rp)'];
        foreach (array_values($headers) as $index => $label) {
            $col = chr(ord('A') + $index);
            $sheet->setCellValue("{$col}{$row}", $label);
        }
        $sheet->getStyle("A{$row}:F{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:F{$row}")->getFill()
            ->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB(self::HEADER_FILL);
        $row++;

        $row = self::writeNodes($sheet, $items, $row);

        $sheet->setCellValue("A{$row}", 'TOTAL');
        $sheet->mergeCells("A{$row}:E{$row}");
        $sheet->setCellValue("F{$row}", (float) $quotation->grand_total);
        $sheet->getStyle("A{$row}:F{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:F{$row}")->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
        $sheet->getStyle("F{$tableHeaderRow}:F{$row}")->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("E{$tableHeaderRow}:E{$row}")->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("C{$tableHeaderRow}:C{$row}")->getNumberFormat()->setFormatCode('#,##0');

        return $spreadsheet;
    }

    // Recursively writes the item tree, indenting names by depth, and returns the next free row.
    private static function writeNodes($sheet, Collection $nodes, int $row): int
    {
        foreach ($nodes as $node) {
            $sheet->setCellValue("A{$row}", $node->code.'.');
            $sheet->setCellValue("B{$row}", $node->name);
            $sheet->getStyle("B{$row}")->getAlignment()->setIndent($node->depth);

            if ($node->qty !== null) {
                $sheet->setCellValue("C{$row}", $node->qty);
            }

            if ($node->unit) {
                $sheet->setCellValue("D{$row}", $node->unit);
            }

            if ($node->qty !== null && (float) $node->unit_price > 0) {
                $sheet->setCellValue("E{$row}", (float) $node->unit_price);
            }

            if ($node->total > 0) {
                $sheet->setCellValue("F{$row}", $node->total);
            }

            $row++;
            $row = self::writeNodes($sheet, $node->children, $row);
        }

        return $row;
    }

    private static function formatMeasurement(mixed $value, ?string $unit): string
    {
        return $value ? "{$value} {$unit}" : '-';
    }
}
