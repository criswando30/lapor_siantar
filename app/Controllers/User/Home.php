<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\KategoriModel;
use App\Models\SubKategoriModel;
use App\Models\LaporanModel;
use App\Models\LampiranModel;
use App\Models\LogStatusModel;
use App\Models\BeritaModel;


class Home extends BaseController
{
    public function index(): string
    {
        $kategoriList = (new KategoriModel())
            ->select('id, nama_kategori')
            ->orderBy('nama_kategori', 'ASC')
            ->findAll();

        // statistik
        $db = db_connect();
        $rows = $db->table('laporan')
            ->select('status, COUNT(*) as total')
            ->groupBy('status')
            ->get()->getResultArray();

        $stat = [
            'laporan_diterima' => 0,
            'diverifikasi' => 0,
            'dalam_proses' => 0,
            'selesai' => 0,
        ];
        foreach ($rows as $r) {
            $stat[$r['status']] = (int) $r['total'];
        }

        // ✅ berita terbaru
        $beritaTerbaru = (new BeritaModel())->getLatestPublic(6);

        return view('user/pages/home', [
            'title' => 'Beranda',
            'kategoriList' => $kategoriList,
            'stat' => $stat,
            'beritaTerbaru' => $beritaTerbaru, // ✅ WAJIB
        ]);
    }


    public function subkategori()
    {
        $kategoriId = (int) $this->request->getGet('kategori_id');
        if ($kategoriId <= 0) {
            return $this->response->setJSON([]);
        }

        $subs = (new SubKategoriModel())->byKategori($kategoriId);
        return $this->response->setJSON($subs);
    }

    public function submitLaporan()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(site_url('/'))->with('login_error', 'Silakan login dulu.');
        }

        $rules = [
            'kategori_id' => 'required|is_natural_no_zero',
            'sub_kategori_id' => 'required|is_natural_no_zero',
            'deskripsi' => 'required',
            'tanggal_kejadian' => 'required|valid_date',
            'lokasi' => 'required|max_length[255]',
            'alamat_lengkap' => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(site_url('/'))
                ->withInput()
                ->with('lapor_error', $this->validator->listErrors());
        }

        // kode tiket unik (varchar 30)
        $kodeTiket = 'EGV-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));

        $laporanModel = new LaporanModel();
        $lampiranModel = new LampiranModel();
        $logModel = new LogStatusModel();

        $laporanId = $laporanModel->insert([
            'kode_tiket' => $kodeTiket,
            'user_id' => (int) session('user_id'),
            'kategori_id' => (int) $this->request->getPost('kategori_id'),
            'sub_kategori_id' => (int) $this->request->getPost('sub_kategori_id'),
            'lokasi' => (string) $this->request->getPost('lokasi'),
            'alamat_lengkap' => $this->request->getPost('alamat_lengkap') ?: null,
            'latitude' => $this->request->getPost('latitude') ?: null,
            'longitude' => $this->request->getPost('longitude') ?: null,
            'deskripsi' => (string) $this->request->getPost('deskripsi'),
            'tanggal_kejadian' => (string) $this->request->getPost('tanggal_kejadian'),
            'status' => 'laporan_diterima',
        ], true);


        // log status awal
        $logModel->insert([
            'laporan_id' => $laporanId,
            'status' => 'laporan_diterima',
            'keterangan' => 'Laporan dibuat oleh pelapor.',
            'diubah_oleh' => null,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // upload lampiran (gambar max 2MB)
        $files = $this->request->getFiles();
        if (isset($files['lampiran'])) {
            foreach ($files['lampiran'] as $file) {
                if (!$file->isValid())
                    continue;
                if ($file->getSize() > 2 * 1024 * 1024)
                    continue;

                $mime = $file->getMimeType();
                if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp', 'image/gif'], true))
                    continue;

                $newName = $file->getRandomName();
                $targetDir = FCPATH . 'uploads/laporan';

                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0775, true);
                }

                $file->move($targetDir, $newName);

                $lampiranModel->insert([
                    'laporan_id' => $laporanId,
                    'nama_file' => $file->getClientName(),
                    'path_file' => 'uploads/laporan/' . $newName,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        return redirect()->to(site_url('status?kode=' . urlencode($kodeTiket)))
            ->with('login_success', 'Laporan berhasil dikirim. Simpan kode tiket Anda: ' . $kodeTiket);
    }
}
