<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BeritaModel;

class Berita extends BaseController
{
    public function index()
    {
        $m = new \App\Models\BeritaModel();

        $q = trim((string) $this->request->getGet('q'));
        $status = (string) $this->request->getGet('status');
        $from = (string) $this->request->getGet('from');
        $to = (string) $this->request->getGet('to');

        // ✅ KPI pakai query builder BARU (jangan pakai $m yang sama)
        $db = \Config\Database::connect();
        $kpiRows = $db->table('berita')
            ->select('status, COUNT(*) AS total')
            ->groupBy('status')
            ->get()
            ->getResultArray();

        $stats = ['total' => 0, 'draft' => 0, 'publish' => 0];
        foreach ($kpiRows as $r) {
            $stats['total'] += (int) $r['total'];
            if ($r['status'] === 'draft')
                $stats['draft'] = (int) $r['total'];
            if ($r['status'] === 'publish')
                $stats['publish'] = (int) $r['total'];
        }

        // ✅ LIST baru
        $m->getAdminList([
            'q' => $q,
            'status' => $status,
            'from' => $from,
            'to' => $to,
        ]);

        $rows = $m->paginate(10, 'berita_admin');
        $pager = $m->pager;

        return view('admin/pages/berita', [
            'title' => 'Manajemen Berita',
            'rows' => $rows,
            'pager' => $pager,
            'stats' => $stats,
            'filters' => ['q' => $q, 'status' => $status, 'from' => $from, 'to' => $to],
            'userName' => session('nama') ?: 'Admin',
            'userRole' => session('role') ?: 'admin',
        ]);
    }


    public function create()
    {
        return view('admin/pages/berita_form', [
            'title' => 'Tambah Berita',
            'mode' => 'create',
            'row' => null,
            'userName' => session('nama') ?: 'Admin',
            'userRole' => session('role') ?: 'admin',
        ]);
    }

    public function store()
    {
        $rules = [
            'judul' => 'required|min_length[5]|max_length[200]',
            'ringkas' => 'permit_empty',
            'isi' => 'required',
            'status' => 'required|in_list[draft,publish]',
            'gambar' => 'permit_empty|uploaded[gambar]|max_size[gambar,2048]|is_image[gambar]',
        ];

        // gambar optional: jadi kalau tidak upload jangan divalidasi uploaded[]
        $hasFile = $this->request->getFile('gambar') && $this->request->getFile('gambar')->isValid();
        if (!$hasFile)
            unset($rules['gambar']);
        else
            $rules['gambar'] = 'max_size[gambar,2048]|is_image[gambar]';

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('login_error', $this->validator->listErrors());
        }

        $m = new BeritaModel();

        $judul = (string) $this->request->getPost('judul');
        $baseSlug = $m->makeSlug($judul);
        $slug = $m->uniqueSlug($baseSlug);

        $status = (string) $this->request->getPost('status');

        // upload gambar jika ada
        $path = null;
        if ($hasFile) {
            $file = $this->request->getFile('gambar');
            $newName = $file->getRandomName();

            $targetDir = FCPATH . 'uploads/berita';
            if (!is_dir($targetDir))
                mkdir($targetDir, 0775, true);

            $file->move($targetDir, $newName);
            $path = 'uploads/berita/' . $newName;
        }

        $data = [
            'judul' => $judul,
            'slug' => $slug,
            'ringkas' => $this->request->getPost('ringkas') ?: null,
            'isi' => (string) $this->request->getPost('isi'),
            'gambar' => $path,
            'status' => $status,
            'tanggal_publish' => ($status === 'publish') ? date('Y-m-d H:i:s') : null,
        ];

        $id = $m->insert($data, true);

        return redirect()->to(site_url('admin/berita/' . $id))
            ->with('login_success', 'Berita berhasil dibuat.');
    }

    public function detail(int $id)
    {
        $m = new BeritaModel();
        $row = $m->find($id);

        if (!$row) {
            return redirect()->to(site_url('admin/berita'))->with('login_error', 'Berita tidak ditemukan.');
        }

        return view('admin/pages/berita_detail', [
            'title' => 'Detail Berita',
            'row' => $row,
            'userName' => session('nama') ?: 'Admin',
            'userRole' => session('role') ?: 'admin',
        ]);
    }

    public function edit(int $id)
    {
        $m = new BeritaModel();
        $row = $m->find($id);

        if (!$row) {
            return redirect()->to(site_url('admin/berita'))->with('login_error', 'Berita tidak ditemukan.');
        }

        return view('admin/pages/berita_form', [
            'title' => 'Edit Berita',
            'mode' => 'edit',
            'row' => $row,
            'userName' => session('nama') ?: 'Admin',
            'userRole' => session('role') ?: 'admin',
        ]);
    }

    public function update(int $id)
    {
        $m = new BeritaModel();
        $row = $m->find($id);

        if (!$row) {
            return redirect()->to(site_url('admin/berita'))->with('login_error', 'Berita tidak ditemukan.');
        }

        $rules = [
            'judul' => 'required|min_length[5]|max_length[200]',
            'ringkas' => 'permit_empty',
            'isi' => 'required',
            'status' => 'required|in_list[draft,publish]',
        ];

        $file = $this->request->getFile('gambar');
        $hasFile = $file && $file->isValid();
        if ($hasFile) {
            $rules['gambar'] = 'max_size[gambar,2048]|is_image[gambar]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('login_error', $this->validator->listErrors());
        }

        $judul = (string) $this->request->getPost('judul');
        $baseSlug = $m->makeSlug($judul);
        $slug = $m->uniqueSlug($baseSlug, $id);

        // upload gambar baru jika ada
        $path = $row['gambar'] ?? null;
        if ($hasFile) {
            $newName = $file->getRandomName();
            $targetDir = FCPATH . 'uploads/berita';
            if (!is_dir($targetDir))
                mkdir($targetDir, 0775, true);

            $file->move($targetDir, $newName);
            $path = 'uploads/berita/' . $newName;

            // opsional: hapus file lama
            if (!empty($row['gambar'])) {
                $old = FCPATH . $row['gambar'];
                if (is_file($old))
                    @unlink($old);
            }
        }

        $status = (string) $this->request->getPost('status');

        $update = [
            'judul' => $judul,
            'slug' => $slug,
            'ringkas' => $this->request->getPost('ringkas') ?: null,
            'isi' => (string) $this->request->getPost('isi'),
            'gambar' => $path,
            'status' => $status,
        ];

        // jika baru publish dan tanggal_publish kosong -> set now
        if ($status === 'publish' && empty($row['tanggal_publish'])) {
            $update['tanggal_publish'] = date('Y-m-d H:i:s');
        }
        // jika kembali draft -> kosongkan tanggal_publish (opsional)
        if ($status === 'draft') {
            $update['tanggal_publish'] = null;
        }

        $m->update($id, $update);

        return redirect()->to(site_url('admin/berita/' . $id))
            ->with('login_success', 'Berita berhasil diperbarui.');
    }

    public function toggle(int $id)
    {
        $m = new BeritaModel();
        $row = $m->find($id);

        if (!$row) {
            return redirect()->to(site_url('admin/berita'))->with('login_error', 'Berita tidak ditemukan.');
        }

        $newStatus = ($row['status'] === 'publish') ? 'draft' : 'publish';

        $data = ['status' => $newStatus];

        if ($newStatus === 'publish') {
            $data['tanggal_publish'] = $row['tanggal_publish'] ?: date('Y-m-d H:i:s');
        } else {
            $data['tanggal_publish'] = null;
        }

        $m->update($id, $data);

        return redirect()->back()->with('login_success', 'Status berita diubah menjadi: ' . strtoupper($newStatus));
    }

    public function delete(int $id)
    {
        $m = new BeritaModel();
        $row = $m->find($id);

        if (!$row) {
            return redirect()->to(site_url('admin/berita'))->with('login_error', 'Berita tidak ditemukan.');
        }

        // hapus gambar jika ada
        if (!empty($row['gambar'])) {
            $old = FCPATH . $row['gambar'];
            if (is_file($old))
                @unlink($old);
        }

        $m->delete($id);

        return redirect()->to(site_url('admin/berita'))->with('login_success', 'Berita berhasil dihapus.');
    }
}
