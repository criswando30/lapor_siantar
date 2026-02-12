<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\InstansiModel;
use App\Models\KategoriModel;
use App\Models\LaporanModel;
use App\Models\LaporanTimelineModel;
use App\Models\LaporanLampiranModel;

class Home extends BaseController
{
    public function index(): string
    {
        $instansi = (new InstansiModel())->getActive();
        $kategori = (new KategoriModel())->getActive();

        return view('user/pages/home', [
            'title' => 'Beranda',
            'instansiList' => $instansi,
            'kategoriList' => $kategori,
        ]);
    }

    public function submitLaporan()
    {
        // route sudah pakai filter, guard tambahan aman
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(site_url('/'))->with('login_error', 'Silakan login dulu.');
        }

        $rules = [
            'judul' => 'required|max_length[200]',
            'isi' => 'required',
            'tanggal_kejadian' => 'required|valid_date[Y-m-d]',
            'lokasi_kejadian' => 'required|max_length[255]',
            'instansi_id' => 'required|is_natural_no_zero',
            'kategori_id' => 'required|is_natural_no_zero',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(site_url('/'))
                ->withInput()
                ->with('lapor_error', $this->validator->listErrors());
        }

        $laporanModel  = new LaporanModel();
        $timelineModel = new LaporanTimelineModel();
        $lampiranModel = new LaporanLampiranModel();

        // kode unik (varchar 30)
        $kode = 'LPS-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));

        $laporanId = $laporanModel->insert([
            'kode'            => $kode,
            'user_id'         => (int) session('user_id'),
            'judul'           => (string) $this->request->getPost('judul'),
            'isi'             => (string) $this->request->getPost('isi'),
            'tanggal_kejadian'=> (string) $this->request->getPost('tanggal_kejadian'),
            'lokasi_kejadian' => (string) $this->request->getPost('lokasi_kejadian'),
            'instansi_id'     => (int) $this->request->getPost('instansi_id'),
            'kategori_id'     => (int) $this->request->getPost('kategori_id'),
            'status'          => 'pending',
        ], true);

        // timeline awal
        $timelineModel->insert([
            'laporan_id' => $laporanId,
            'status'     => 'pending',
            'title'      => 'Laporan Dikirim',
            'note'       => 'Laporan berhasil dibuat dan masuk antrean verifikasi.',
            'icon'       => 'check',
            'state'      => 'done',
            'created_by' => (int) session('user_id'),
        ]);

        // upload lampiran (gambar max 2MB)
        $files = $this->request->getFiles();
        if (isset($files['lampiran'])) {
            foreach ($files['lampiran'] as $file) {
                if (!$file->isValid()) continue;
                if ($file->getSize() > 2 * 1024 * 1024) continue;

                $mime = $file->getMimeType();
                if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp', 'image/gif'], true)) continue;

                $newName   = $file->getRandomName();
                $targetDir = FCPATH . 'uploads/laporan';

                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0775, true);
                }

                $file->move($targetDir, $newName);

                $lampiranModel->insert([
                    'laporan_id' => $laporanId,
                    'file_name'  => $file->getClientName(),
                    'file_path'  => 'uploads/laporan/' . $newName,
                    'mime_type'  => $mime,
                    'file_size'  => $file->getSize(),
                ]);
            }
        }

        return redirect()->to(site_url('status?kode=' . urlencode($kode)))
            ->with('login_success', 'Laporan berhasil dikirim. Simpan kode tiket Anda: ' . $kode);
    }
}
