<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LaporanModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $laporanModel = new LaporanModel();

        // Statistik KPI
        $stats = [
            'total'      => $laporanModel->countAllResults(),
            'verifikasi' => $laporanModel->where('status', 'pending')->countAllResults(),
            'diproses'   => $laporanModel->where('status', 'diproses')->countAllResults(),
            'selesai'    => $laporanModel->where('status', 'selesai')->countAllResults(),
        ];

        // Aktivitas laporan terbaru (ambil dari tabel laporan)
        $rows = $laporanModel
            ->select('id, judul, status, created_at')
            ->orderBy('created_at', 'DESC')
            ->findAll(5);

        $aktivitas = array_map(function ($r) {
            return [
                'id'     => $r['id'],
                'judul'  => $r['judul'],
                'waktu'  => $this->timeAgo($r['created_at']),
                'status' => $this->labelStatus($r['status']),
            ];
        }, $rows);

        // View kamu sekarang: app/Views/admin/dashboard.php
        return view('admin/pages/dashboard', [
            'title'     => 'Dashboard Admin',
            'stats'     => $stats,
            'aktivitas' => $aktivitas,
        ]);
    }

    private function labelStatus(string $status): string
    {
        return match ($status) {
            'pending'  => 'Menunggu Verifikasi',
            'diterima' => 'Diterima',
            'diproses' => 'Sedang Diproses',
            'selesai'  => 'Selesai',
            'ditolak'  => 'Ditolak',
            default    => ucfirst($status),
        };
    }

    private function timeAgo(?string $datetime): string
    {
        if (!$datetime) return '-';

        try {
            $dt  = new \DateTime($datetime);
            $now = new \DateTime();

            $diff = $now->getTimestamp() - $dt->getTimestamp();
            if ($diff < 60) return $diff . 'd yang lalu';

            $mins = intdiv($diff, 60);
            if ($mins < 60) return $mins . 'm yang lalu';

            $hours = intdiv($mins, 60);
            if ($hours < 24) return $hours . 'j yang lalu';

            $days = intdiv($hours, 24);
            return $days . 'h yang lalu';
        } catch (\Throwable $e) {
            return $datetime;
        }
    }
}
