<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\LaporanModel;
use App\Models\KategoriModel;
use App\Models\InstansiModel;
use App\Models\LaporanLampiranModel;
use App\Models\LaporanTimelineModel;

class Pengaduan extends BaseController
{
    protected LaporanModel $laporanModel;
    protected KategoriModel $kategoriModel;
    protected InstansiModel $instansiModel;
    protected LaporanLampiranModel $lampiranModel;
    protected LaporanTimelineModel $timelineModel;

    public function __construct()
    {
        $this->laporanModel  = new LaporanModel();
        $this->kategoriModel = new KategoriModel();
        $this->instansiModel = new InstansiModel();
        $this->lampiranModel = new LaporanLampiranModel();
        $this->timelineModel = new LaporanTimelineModel();
    }

    public function index()
    {
        $q        = trim((string) $this->request->getGet('q'));
        $status   = (string) $this->request->getGet('status');    // pending|diterima|diproses|selesai|ditolak
        $kategori = (string) $this->request->getGet('kategori');  // kategori_id
        $start    = (string) $this->request->getGet('start');     // YYYY-MM-DD
        $end      = (string) $this->request->getGet('end');       // YYYY-MM-DD

        $builder = $this->laporanModel
            ->select('
                laporan.id,
                laporan.kode,
                laporan.judul,
                laporan.isi,
                laporan.status,
                laporan.created_at,
                users.nama_lengkap as pelapor_nama,
                users.no_telp as pelapor_telp,
                kategori.nama as kategori_nama,
                instansi.nama as instansi_nama
            ')
            ->join('users', 'users.id = laporan.user_id', 'left')
            ->join('kategori', 'kategori.id = laporan.kategori_id', 'left')
            ->join('instansi', 'instansi.id = laporan.instansi_id', 'left');

        if ($q !== '') {
            $builder->groupStart()
                ->like('laporan.kode', $q)
                ->orLike('laporan.judul', $q)
                ->orLike('laporan.isi', $q)
                ->orLike('users.nama_lengkap', $q)
                ->groupEnd();
        }

        $allowedStatus = ['pending','diterima','diproses','selesai','ditolak'];
        if ($status !== '' && in_array($status, $allowedStatus, true)) {
            $builder->where('laporan.status', $status);
        }

        if ($kategori !== '' && ctype_digit($kategori)) {
            $builder->where('laporan.kategori_id', (int) $kategori);
        }

        if ($start !== '' && $end !== '') {
            $builder->where('laporan.created_at >=', $start.' 00:00:00')
                    ->where('laporan.created_at <=', $end.' 23:59:59');
        } elseif ($start !== '') {
            $builder->where('laporan.created_at >=', $start.' 00:00:00');
        } elseif ($end !== '') {
            $builder->where('laporan.created_at <=', $end.' 23:59:59');
        }

        $perPage = 10;
        $rowsRaw = $builder->orderBy('laporan.created_at', 'DESC')->paginate($perPage, 'laporan');
        $pager   = $this->laporanModel->pager;

        // KPI (tanpa filter)
        $kpi = [
            'total'    => $this->laporanModel->countAllResults(),
            'pending'  => $this->laporanModel->where('status', 'pending')->countAllResults(),
            'diproses' => $this->laporanModel->where('status', 'diproses')->countAllResults(),
            'selesai'  => $this->laporanModel->where('status', 'selesai')->countAllResults(),
        ];

        $kategoriList = $this->kategoriModel->where('aktif', 1)->orderBy('nama','ASC')->findAll();

        $rows = array_map(function ($r) {
            return [
                'id'        => $r['id'],
                'kode'      => $r['kode'],
                'tanggal'   => date('d M Y, H:i', strtotime($r['created_at'])),
                'pelapor'   => $r['pelapor_nama'] ?? '-',
                'kontak'    => $r['pelapor_telp'] ?? '-',
                'kategori'  => $r['kategori_nama'] ?? '-',
                'deskripsi' => $r['judul'],
                'status'    => $r['status'],
                'petugas'   => $r['instansi_nama'] ?? '-',
            ];
        }, $rowsRaw ?? []);

        return view('admin/pages/pengaduan', [
            'title' => 'Manajemen Pengaduan',
            'kpi'   => [
                'total'     => $kpi['total'],
                'menunggu'  => $kpi['pending'],
                'diproses'  => $kpi['diproses'],
                'selesai'   => $kpi['selesai'],
            ],
            'rows'         => $rows,
            'pager'        => $pager,
            'kategoriList' => $kategoriList,
            'filters'      => [
                'q'        => $q,
                'status'   => $status,
                'kategori' => $kategori,
                'start'    => $start,
                'end'      => $end,
            ],
        ]);
    }

    // GET admin/pengaduan/{id}
    public function detail(int $id)
    {
        $laporan = $this->laporanModel
            ->select('
                laporan.*,
                users.nama_lengkap as pelapor_nama,
                users.no_telp as pelapor_telp,
                users.email as pelapor_email,
                kategori.nama as kategori_nama,
                instansi.nama as instansi_nama
            ')
            ->join('users', 'users.id = laporan.user_id', 'left')
            ->join('kategori', 'kategori.id = laporan.kategori_id', 'left')
            ->join('instansi', 'instansi.id = laporan.instansi_id', 'left')
            ->where('laporan.id', $id)
            ->first();

        if (!$laporan) {
            return redirect()->to(base_url('admin/pengaduan'))->with('error', 'Laporan tidak ditemukan.');
        }

        $lampiran = $this->lampiranModel->where('laporan_id', $id)->orderBy('id', 'DESC')->findAll();
        $timeline = $this->timelineModel->where('laporan_id', $id)->orderBy('created_at', 'ASC')->findAll();

        // kalau kamu belum punya view detail, boleh sementara dd($laporan);
        return view('admin/pages/pengaduan_detail', [
            'title'    => 'Detail Pengaduan',
            'laporan'  => $laporan,
            'lampiran' => $lampiran,
            'timeline' => $timeline,
        ]);
    }

    // POST admin/pengaduan/{id}/status
    public function updateStatus(int $id)
    {
        $newStatus = (string) $this->request->getPost('status');
        $note      = trim((string) $this->request->getPost('note'));

        $allowedStatus = ['pending','diterima','diproses','selesai','ditolak'];
        if (!in_array($newStatus, $allowedStatus, true)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $laporan = $this->laporanModel->find($id);
        if (!$laporan) {
            return redirect()->to(base_url('admin/pengaduan'))->with('error', 'Laporan tidak ditemukan.');
        }

        $this->laporanModel->update($id, ['status' => $newStatus]);

        $titleMap = [
            'pending'  => 'Menunggu Verifikasi',
            'diterima' => 'Laporan Diterima',
            'diproses' => 'Laporan Diproses',
            'selesai'  => 'Laporan Selesai',
            'ditolak'  => 'Laporan Ditolak',
        ];

        $this->timelineModel->insert([
            'laporan_id' => $id,
            'status'     => $newStatus,
            'title'      => $titleMap[$newStatus] ?? 'Update Status',
            'note'       => $note !== '' ? $note : null,
            'icon'       => ($newStatus === 'selesai') ? 'finish' : (($newStatus === 'diproses') ? 'process' : 'check'),
            'state'      => 'done',
            'created_by' => session('user_id') ?: null,
        ]);

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }
}
