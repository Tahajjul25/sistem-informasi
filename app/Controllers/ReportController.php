<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Security;
use App\Models\Report;

class ReportController extends BaseController
{
    public function index(): void
    {
        Auth::requireLogin();
        [$from, $to] = $this->range();

        $this->view('reports/index', [
            'from'      => $from,
            'to'        => $to,
            'crops'     => Report::cropSummary(),
            'plantings' => Report::plantingRange($from, $to),
            'harvests'  => Report::harvestRange($from, $to),
            'rainfall'  => Report::rainfallRange($from, $to),
        ], 'Laporan');
    }

    public function exportCsv(): void
    {
        Auth::requireLogin();
        [$from, $to] = $this->range();

        $rows = Report::plantingRange($from, $to);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="laporan_tanam.csv"');

        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF"); // BOM agar terbaca benar di Excel
        fputcsv($out, ['Tanggal Tanam', 'Komoditas', 'Luas Lahan (ha)']);
        foreach ($rows as $row) {
            fputcsv($out, [$row['planting_date'], $row['crop_name'], $row['land_area']]);
        }
        fclose($out);
        exit;
    }

    /** Rentang tanggal laporan dari query string; default satu bulan berjalan. */
    private function range(): array
    {
        $from = trim((string) ($_GET['from'] ?? ''));
        $to = trim((string) ($_GET['to'] ?? ''));

        if (!Security::isValidDate($from)) {
            $from = date('Y-m-01');
        }
        if (!Security::isValidDate($to)) {
            $to = date('Y-m-t');
        }

        return [$from, $to];
    }
}
