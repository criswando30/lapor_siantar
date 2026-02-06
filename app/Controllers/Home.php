<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('pages/home', ['title' => 'Beranda']);
    }
    public function status()
{
    $kode = $this->request->getGet('kode');

    // sementara dummy (nanti backend ganti query)
    $laporan = null;
    if ($kode) {
        $laporan = [
            'kode' => $kode,
            'judul' => 'Contoh laporan',
            'instansi' => 'Dinas Komunikasi dan Informatika',
            'status' => 'diproses',
            'status_label' => 'Diproses',
            'updated_at' => date('Y-m-d H:i'),
            'tanggal_kejadian' => '2026-02-01',
            'lokasi' => 'Pematangsiantar',
            'isi' => 'Ini contoh isi laporan untuk tampilan status.',
            'timeline' => [
                ['title' => 'Laporan Dikirim', 'time' => '2026-02-01 10:00', 'note' => 'Laporan diterima sistem.'],
                ['title' => 'Diverifikasi', 'time' => '2026-02-01 11:30', 'note' => 'Laporan valid dan diteruskan.'],
                ['title' => 'Diproses Instansi', 'time' => '2026-02-02 09:00', 'note' => 'Sedang ditindaklanjuti.'],
            ],
            'lampiran' => [
                ['name' => 'foto1.png', 'url' => base_url('assets/img/hero.png')],
            ],
        ];
    }

    return view('pages/status', [
        'title' => 'Status Laporan - LaporSiantar',
        'kode' => $kode,
        'laporan' => $laporan,
    ]);
}

}
