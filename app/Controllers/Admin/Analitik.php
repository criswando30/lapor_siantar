<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Analitik extends BaseController
{
    private function buildFilters(): array
    {
        $from      = (string) $this->request->getGet('from');
        $to        = (string) $this->request->getGet('to');
        $status    = (string) $this->request->getGet('status');
        $kategoriId= (string) $this->request->getGet('kategori_id');

        // default: 30 hari terakhir
        if ($from === '' && $to === '') {
            $to   = date('Y-m-d');
            $from = date('Y-m-d', strtotime('-29 days'));
        }

        return [
            'from'        => $from,
            'to'          => $to,
            'status'      => $status,
            'kategori_id' => $kategoriId,
        ];
    }

    private function applyWhere($builder, array $filters)
    {
        // filter tanggal pakai DATE(created_at)
        if (!empty($filters['from'])) {
            $builder->where('DATE(l.created_at) >=', $filters['from']);
        }
        if (!empty($filters['to'])) {
            $builder->where('DATE(l.created_at) <=', $filters['to']);
        }
        if (!empty($filters['status'])) {
            $builder->where('l.status', $filters['status']);
        }
        if (!empty($filters['kategori_id'])) {
            $builder->where('l.kategori_id', (int) $filters['kategori_id']);
        }

        return $builder;
    }

    private function getData(array $filters): array
    {
        $db = db_connect();

        // KPI status
        $kpiQ = $db->table('laporan l')
            ->select('l.status, COUNT(*) as total')
            ->groupBy('l.status');
        $this->applyWhere($kpiQ, $filters);
        $kpiRows = $kpiQ->get()->getResultArray();

        $kpi = [
            'total'           => 0,
            'laporan_diterima'=> 0,
            'diverifikasi'    => 0,
            'dalam_proses'    => 0,
            'selesai'         => 0,
        ];
        foreach ($kpiRows as $r) {
            $kpi['total'] += (int) ($r['total'] ?? 0);
            $st = (string) ($r['status'] ?? '');
            if (array_key_exists($st, $kpi)) {
                $kpi[$st] = (int) ($r['total'] ?? 0);
            }
        }

        // Tren harian
        $trendQ = $db->table('laporan l')
            ->select("DATE(l.created_at) as tanggal, COUNT(*) as total")
            ->groupBy("DATE(l.created_at)")
            ->orderBy("tanggal", "ASC");
        $this->applyWhere($trendQ, $filters);
        $trend = $trendQ->get()->getResultArray();

        // Rekap per kategori
        $byKategoriQ = $db->table('laporan l')
            ->select('k.nama_kategori, COUNT(*) as total')
            ->join('kategori k', 'k.id = l.kategori_id', 'left')
            ->groupBy('l.kategori_id')
            ->orderBy('total', 'DESC');
        $this->applyWhere($byKategoriQ, $filters);
        $byKategori = $byKategoriQ->get()->getResultArray();

        // Daftar detail untuk tabel (untuk export juga)
        $detailQ = $db->table('laporan l')
            ->select('l.id, l.kode_tiket, l.created_at, l.status, k.nama_kategori, sk.nama_subkategori, l.lokasi')
            ->join('kategori k', 'k.id = l.kategori_id', 'left')
            ->join('sub_kategori sk', 'sk.id = l.sub_kategori_id', 'left')
            ->orderBy('l.created_at', 'DESC')
            ->limit(2000);
        $this->applyWhere($detailQ, $filters);
        $details = $detailQ->get()->getResultArray();

        return compact('kpi', 'trend', 'byKategori', 'details');
    }

    public function index()
    {
        $filters = $this->buildFilters();

        // dropdown kategori
        $db = db_connect();
        $kategoriList = $db->table('kategori')
            ->select('id, nama_kategori')
            ->orderBy('nama_kategori', 'ASC')
            ->get()->getResultArray();

        $data = $this->getData($filters);

        return view('admin/pages/analitik', [
            'title'       => 'Laporan Analitik',
            'filters'     => $filters,
            'kategoriList'=> $kategoriList,
            'kpi'         => $data['kpi'],
            'trend'       => $data['trend'],
            'byKategori'  => $data['byKategori'],
            'details'     => $data['details'],
            'userName'    => session('nama') ?: 'Admin',
            'userRole'    => session('role') ?: 'admin',
        ]);
    }

    // ==========================
    // EXPORT EXCEL
    // ==========================
    public function exportExcel()
    {
        $filters = $this->buildFilters();
        $data    = $this->getData($filters);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Analitik');

        $row = 1;
        $sheet->setCellValue("A{$row}", "LAPORAN ANALITIK");
        $sheet->mergeCells("A{$row}:G{$row}");
        $row++;

        $sheet->setCellValue("A{$row}", "Periode");
        $sheet->setCellValue("B{$row}", ($filters['from'] ?? '-') . " s/d " . ($filters['to'] ?? '-'));
        $row += 2;

        // KPI
        $sheet->setCellValue("A{$row}", "KPI");
        $row++;
        $sheet->fromArray([
            ['Total', 'Diterima', 'Diverifikasi', 'Diproses', 'Selesai'],
            [
                $data['kpi']['total'],
                $data['kpi']['laporan_diterima'],
                $data['kpi']['diverifikasi'],
                $data['kpi']['dalam_proses'],
                $data['kpi']['selesai'],
            ]
        ], null, "A{$row}");
        $row += 3;

        // Rekap kategori
        $sheet->setCellValue("A{$row}", "Rekap Per Kategori");
        $row++;
        $sheet->fromArray([['Kategori', 'Total']], null, "A{$row}");
        $row++;
        foreach ($data['byKategori'] as $r) {
            $sheet->fromArray([[
                $r['nama_kategori'] ?? '-',
                (int) ($r['total'] ?? 0)
            ]], null, "A{$row}");
            $row++;
        }
        $row += 2;

        // Detail
        $sheet->setCellValue("A{$row}", "Detail (maks 2000 baris)");
        $row++;
        $sheet->fromArray([['ID', 'Kode Tiket', 'Tanggal', 'Status', 'Kategori', 'Sub Kategori', 'Lokasi']], null, "A{$row}");
        $row++;

        foreach ($data['details'] as $d) {
            $sheet->fromArray([[
                $d['id'] ?? '',
                $d['kode_tiket'] ?? '',
                $d['created_at'] ?? '',
                $d['status'] ?? '',
                $d['nama_kategori'] ?? '',
                $d['nama_subkategori'] ?? '',
                $d['lokasi'] ?? '',
            ]], null, "A{$row}");
            $row++;
        }

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'laporan_analitik_' . date('Ymd_His') . '.xlsx';
        $writer   = new Xlsx($spreadsheet);

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($this->writeToString($writer));
    }

    private function writeToString($writer): string
    {
        ob_start();
        $writer->save('php://output');
        return (string) ob_get_clean();
    }

    // ==========================
    // EXPORT PDF (DOWNLOAD)
    // ==========================
    public function exportPdf()
    {
        $filters = $this->buildFilters();
        $data    = $this->getData($filters);

        // render html dari view khusus pdf
        $html = view('admin/pages/analitik_pdf', [
            'filters'    => $filters,
            'kpi'        => $data['kpi'],
            'byKategori' => $data['byKategori'],
            'details'    => $data['details'],
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'laporan_analitik_' . date('Ymd_His') . '.pdf';

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($dompdf->output());
    }
}
