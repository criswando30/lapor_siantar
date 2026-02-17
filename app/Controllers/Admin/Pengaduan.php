<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LaporanModel;
use App\Models\KategoriModel;
use App\Models\LogStatusModel;
use App\Models\LampiranModel;

class Pengaduan extends BaseController
{
    public function index()
    {
        $laporanModel  = new LaporanModel();
        $kategoriModel = new KategoriModel();

        // FILTER dari GET (sesuai view)
        $q         = trim((string) $this->request->getGet('q'));
        $status    = (string) $this->request->getGet('status');
        $kategori  = (int) $this->request->getGet('kategori_id');
        $dateFrom  = (string) $this->request->getGet('from');
        $dateTo    = (string) $this->request->getGet('to');

        // KPI
        $stats = $laporanModel->getAdminKpi();

        // dropdown kategori
        $kategoriList = $kategoriModel
            ->select('id, nama_kategori')
            ->orderBy('nama_kategori', 'ASC')
            ->findAll();

        // LIST + PAGINATION (aman dari double)
        $perPage = 10;

        // PENTING: getAdminList() HARUS return $this (Model)
        // dan DI DALAMNYA ada groupBy('l.id') atau distinct
        $laporanModel->getAdminList([
            'q'           => $q,
            'status'      => $status,
            'kategori_id' => $kategori,
            'from'        => $dateFrom,
            'to'          => $dateTo,
        ]);

        $rows  = $laporanModel->paginate($perPage, 'laporan_admin');
        $pager = $laporanModel->pager;

        return view('admin/pages/pengaduan', [
            'title'        => 'Manajemen Pengaduan',
            'stats'        => $stats,
            'rows'         => $rows,
            'pager'        => $pager,
            'kategoriList' => $kategoriList,
            'filters'      => [
                'q'          => $q,
                'status'     => $status,
                'kategori_id'=> $kategori,
                'from'       => $dateFrom,
                'to'         => $dateTo,
            ],
            'userName' => session('nama') ?: 'Admin',
            'userRole' => session('role') ?: 'admin',
        ]);
    }

    public function detail(int $id)
    {
        $laporanModel  = new LaporanModel();
        $logModel      = new LogStatusModel();
        $lampiranModel = new LampiranModel();

        $laporan = $laporanModel->getAdminDetail($id);
        if (! $laporan) {
            return redirect()->to(site_url('admin/pengaduan'))
                ->with('login_error', 'Data pengaduan tidak ditemukan.');
        }

        $timeline = $logModel->where('laporan_id', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $lampiran = $lampiranModel->where('laporan_id', $id)
            ->orderBy('created_at', 'ASC')
            ->findAll();

        return view('admin/pages/pengaduan_detail', [
            'title'    => 'Detail Pengaduan',
            'laporan'  => $laporan,
            'timeline' => $timeline,
            'lampiran' => $lampiran,
            'userName' => session('nama') ?: 'Admin',
            'userRole' => session('role') ?: 'admin',
        ]);
    }

    public function updateStatus(int $id)
    {
        $rules = [
            'status'     => 'required|in_list[laporan_diterima,diverifikasi,dalam_proses,selesai]',
            'keterangan' => 'permit_empty|max_length[2000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('login_error', $this->validator->listErrors());
        }

        $statusBaru = (string) $this->request->getPost('status');
        $ket        = (string) $this->request->getPost('keterangan');

        $laporanModel = new LaporanModel();
        $logModel     = new LogStatusModel();

        $laporan = $laporanModel->find($id);
        if (! $laporan) {
            return redirect()->to(site_url('admin/pengaduan'))
                ->with('login_error', 'Data pengaduan tidak ditemukan.');
        }

        $dataUpdate = [
            'status'     => $statusBaru,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($statusBaru === 'selesai') {
            $dataUpdate['tanggal_selesai'] = date('Y-m-d H:i:s');
        } else {
            $dataUpdate['tanggal_selesai'] = null;
        }

        $laporanModel->update($id, $dataUpdate);

        $logModel->insert([
            'laporan_id'  => $id,
            'status'      => $statusBaru,
            'keterangan'  => $ket ?: null,
            'diubah_oleh' => session('user_id') ?: null,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('admin/pengaduan/' . $id))
            ->with('login_success', 'Status berhasil diperbarui.');
    }
}
