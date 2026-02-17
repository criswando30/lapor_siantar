<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LaporanModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $laporanModel = new LaporanModel();

        // KPI sesuai enum DB baru
        $kpi = $laporanModel->getAdminKpi();

        $stats = [
            'total' => $kpi['total'],
            'verifikasi' => $kpi['diverifikasi'],
            'diproses' => $kpi['diproses'],
            'selesai' => $kpi['selesai'],
        ];


        // Aktivitas terbaru: gunakan kode_tiket + kategori/subkategori
        $rows = $laporanModel
            ->withKategoriSub()
            ->orderBy('l.created_at', 'DESC')
            ->findAll(5);


        $aktivitas = array_map(function ($r) {
            $judul = $r['kode_tiket'] . ' - ' . ($r['nama_kategori'] ?? '-') . ' / ' . ($r['nama_subkategori'] ?? '-');


            // fallback kalau kategori kosong
            if (trim($judul) === '') {
                $judul = $r['kode_tiket'] ?? 'Laporan';
            }

            return [
                'id' => (int) $r['id'],
                'judul' => $judul,
                'waktu' => $this->timeAgo($r['created_at'] ?? null),
                'status' => $this->labelStatus($r['status'] ?? ''),
            ];
        }, $rows);

        return view('admin/pages/dashboard', [
            'title' => 'Dashboard Admin',
            'stats' => $stats,
            'aktivitas' => $aktivitas,
            'userName' => session('nama') ?: 'Admin',
            'userRole' => session('role') ?: 'admin',
        ]);
    }

    private function labelStatus(string $status): string
    {
        return match ($status) {
            'laporan_diterima' => 'Laporan Diterima',
            'diverifikasi' => 'Menunggu Verifikasi',
            'dalam_proses' => 'Sedang Diproses',
            'selesai' => 'Selesai',
            default => $status ?: '-',
        };
    }

    private function timeAgo(?string $datetime): string
    {
        if (!$datetime)
            return '-';

        try {
            $dt = new \DateTime($datetime);
            $now = new \DateTime();

            $diff = $now->getTimestamp() - $dt->getTimestamp();
            if ($diff < 60)
                return $diff . 'd yang lalu';

            $mins = intdiv($diff, 60);
            if ($mins < 60)
                return $mins . 'm yang lalu';

            $hours = intdiv($mins, 60);
            if ($hours < 24)
                return $hours . 'j yang lalu';

            $days = intdiv($hours, 24);
            return $days . 'h yang lalu';
        } catch (\Throwable $e) {
            return $datetime;
        }
    }
}
