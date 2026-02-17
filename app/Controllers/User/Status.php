<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\LaporanModel;
use App\Models\LogStatusModel;
use App\Models\LampiranModel;

class Status extends BaseController
{
    public function index()
    {
        $kode = trim((string) $this->request->getGet('kode'));
        $laporan = null;

        if ($kode !== '') {
            $laporanModel  = new LaporanModel();
            $logModel      = new LogStatusModel();
            $lampiranModel = new LampiranModel();

            // Ambil laporan berdasarkan kode tiket
            $laporan = $laporanModel->where('kode_tiket', $kode)->first();

            if (! $laporan) {
                return redirect()->to(site_url('status'))
                    ->withInput()
                    ->with('status_error', 'Kode tiket tidak ditemukan.');
            }

            // 1) timeline dari log_status
            $timelineRows = $logModel->where('laporan_id', $laporan['id'])
                ->orderBy('created_at', 'ASC')
                ->findAll();

            // index timeline per status (ambil yang terakhir kalau ada duplikat)
            $byStatus = [];
            foreach ($timelineRows as $tr) {
                if (!empty($tr['status'])) {
                    $byStatus[$tr['status']] = $tr;
                }
            }

            $current = $laporan['status']; // laporan_diterima/diverifikasi/dalam_proses/selesai

            // 2) definisi step UI (sesuai enum DB)
            $steps = [
                'laporan_diterima' => ['title' => 'Laporan Diterima', 'icon' => 'check'],
                'diverifikasi'     => ['title' => 'Diverifikasi', 'icon' => 'check'],
                'dalam_proses'     => ['title' => 'Dalam Proses', 'icon' => 'process'],
                'selesai'          => ['title' => 'Selesai', 'icon' => 'finish'],
            ];

            // 3) state default todo
            $stateMap = array_fill_keys(array_keys($steps), 'todo');

            // tentukan state berdasarkan status saat ini
            $keys = array_keys($steps);
            $posCurrent = array_search($current, $keys, true);

            if ($posCurrent === false) {
                // kalau status aneh, aktifkan step pertama
                $stateMap[$keys[0]] = 'active';
            } else {
                foreach ($keys as $i => $k) {
                    if ($i < $posCurrent) $stateMap[$k] = 'done';
                    elseif ($i === $posCurrent) $stateMap[$k] = ($current === 'selesai') ? 'done' : 'active';
                    else $stateMap[$k] = 'todo';
                }
            }

            // 4) susun timeline final untuk view
            $laporan['timeline'] = [];
            foreach ($steps as $st => $meta) {
                $row = $byStatus[$st] ?? null;

                $laporan['timeline'][] = [
                    'title'  => $meta['title'],
                    'time'   => $row['created_at'] ?? '-',
                    'note'   => $row['keterangan'] ?? $this->defaultNote($st),
                    'icon'   => $meta['icon'],
                    'state'  => $stateMap[$st],
                    'status' => $st,
                ];
            }

            // 5) lampiran (tabel: nama_file, path_file)
            $lampiranRows = $lampiranModel->where('laporan_id', $laporan['id'])
                ->orderBy('created_at', 'ASC')
                ->findAll();

            $laporan['lampiran'] = array_map(function ($f) {
                $path = $f['path_file'] ?? '';
                return [
                    'name' => $f['nama_file'] ?? 'file',
                    'url'  => $path ? base_url($path) : '#',
                ];
            }, $lampiranRows);

            // 6) label status (untuk badge)
            $labels = [
                'laporan_diterima' => 'Diterima',
                'diverifikasi'     => 'Diverifikasi',
                'dalam_proses'     => 'Dalam Proses',
                'selesai'          => 'Selesai',
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
            'laporan_diterima' => 'Laporan telah masuk ke sistem dan menunggu verifikasi petugas.',
            'diverifikasi'     => 'Laporan diverifikasi dan diteruskan untuk ditindaklanjuti.',
            'dalam_proses'     => 'Laporan sedang dalam proses penanganan. Mohon menunggu informasi selanjutnya.',
            'selesai'          => 'Laporan telah selesai ditangani. Terima kasih atas partisipasi Anda.',
            default            => '',
        };
    }
}
