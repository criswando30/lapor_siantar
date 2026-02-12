<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\LaporanModel;
use App\Models\LaporanTimelineModel;
use App\Models\LaporanLampiranModel;

class Status extends BaseController
{
    public function index()
    {
        $kode = trim((string) $this->request->getGet('kode'));
        $laporan = null;

        if ($kode !== '') {
            $laporanModel  = new LaporanModel();
            $timelineModel = new LaporanTimelineModel();
            $lampiranModel = new LaporanLampiranModel();

            $laporan = $laporanModel->findDetailByKode($kode);

            if (!$laporan) {
                return redirect()->to(site_url('status'))
                    ->withInput()
                    ->with('status_error', 'Kode tiket tidak ditemukan.');
            }

            // 1) timeline dari DB
            $timelineRows = $timelineModel->where('laporan_id', $laporan['id'])
                ->orderBy('created_at', 'ASC')
                ->findAll();

            $byStatus = [];
            foreach ($timelineRows as $tr) {
                if (!empty($tr['status'])) {
                    $byStatus[$tr['status']] = $tr;
                }
            }

            $current = $laporan['status']; // pending/diterima/diproses/selesai/ditolak

            // 2) definisi step UI
            $steps = [
                'pending'  => ['title' => 'Laporan Diterima', 'icon' => 'check'],
                'diterima' => ['title' => 'Diverifikasi', 'icon' => 'check'],
                'diproses' => ['title' => 'Dalam Proses', 'icon' => 'process'],
                'selesai'  => ['title' => 'Selesai', 'icon' => 'finish'],
            ];

            // 3) state done/active/todo
            $stateMap = [
                'pending'  => 'todo',
                'diterima' => 'todo',
                'diproses' => 'todo',
                'selesai'  => 'todo',
            ];

            if ($current === 'ditolak') {
                $stateMap['pending'] = 'done';
            } else {
                if ($current === 'pending') {
                    $stateMap['pending'] = 'active';
                } elseif ($current === 'diterima') {
                    $stateMap['pending']  = 'done';
                    $stateMap['diterima'] = 'active';
                } elseif ($current === 'diproses') {
                    $stateMap['pending']  = 'done';
                    $stateMap['diterima'] = 'done';
                    $stateMap['diproses'] = 'active';
                } elseif ($current === 'selesai') {
                    $stateMap['pending']  = 'done';
                    $stateMap['diterima'] = 'done';
                    $stateMap['diproses'] = 'done';
                    $stateMap['selesai']  = 'done';
                }
            }

            // 4) susun timeline final
            $laporan['timeline'] = [];
            foreach ($steps as $st => $meta) {
                $row = $byStatus[$st] ?? null;

                $laporan['timeline'][] = [
                    'title'  => $meta['title'],
                    'time'   => $row['created_at'] ?? '-',
                    'note'   => $row['note'] ?? $this->defaultNote($st),
                    'icon'   => $row['icon'] ?? $meta['icon'],
                    'state'  => $stateMap[$st],
                    'status' => $st,
                ];
            }

            // 5) lampiran
            $lampiranRows = $lampiranModel->where('laporan_id', $laporan['id'])
                ->orderBy('created_at', 'ASC')
                ->findAll();

            $laporan['lampiran'] = array_map(function ($f) {
                $path = $f['file_path'] ?? '';
                return [
                    'name' => $f['file_name'] ?? 'file',
                    'url'  => $path ? base_url($path) : '#',
                ];
            }, $lampiranRows);

            // 6) label status
            $labels = [
                'pending'  => 'Pending',
                'diterima' => 'Diterima',
                'diproses' => 'Sedang Diproses',
                'selesai'  => 'Selesai',
                'ditolak'  => 'Ditolak',
            ];
            $laporan['status_label'] = $labels[$laporan['status']] ?? strtoupper((string) $laporan['status']);
        }

        return view('user/pages/status', [
            'title'   => 'Status Laporan - LaporSiantar',
            'kode'    => $kode,
            'laporan' => $laporan,
        ]);
    }

    private function defaultNote(string $status): string
    {
        return match ($status) {
            'pending'  => 'Laporan telah masuk ke sistem dan menunggu antrean verifikasi petugas.',
            'diterima' => 'Laporan diverifikasi dan diteruskan ke instansi terkait untuk ditindaklanjuti.',
            'diproses' => 'Laporan sedang dalam proses penanganan oleh instansi terkait. Mohon menunggu informasi selanjutnya.',
            'selesai'  => 'Laporan telah selesai ditangani. Terima kasih atas partisipasi Anda.',
            default    => '',
        };
    }
}
