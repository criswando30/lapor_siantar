<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();

        $q      = trim((string) $this->request->getGet('q'));
        $role   = (string) $this->request->getGet('role');     // admin | masyarakat (atau user)
        $active = (string) $this->request->getGet('active');   // 1 | 0

        // mapping: filter UI "active" -> status_akun enum
        $statusAkun = '';
        if ($active === '1') $statusAkun = 'aktif';
        if ($active === '0') $statusAkun = 'nonaktif';

        // Base query
        $userModel->select('id, nama, email, no_hp, role, status_akun, created_at')
                  ->orderBy('created_at', 'DESC');

        if ($q !== '') {
            $userModel->groupStart()
                ->like('nama', $q)
                ->orLike('email', $q)
                ->orLike('no_hp', $q)
            ->groupEnd();
        }

        if ($role !== '') {
            $userModel->where('role', $role);
        }

        if ($statusAkun !== '') {
            $userModel->where('status_akun', $statusAkun);
        }

        // Pagination
        $perPage = 10;
        $rows    = $userModel->paginate($perPage, 'users_admin');
        $pager   = $userModel->pager;

        // KPI
        $stats = [
            'total'    => (new UserModel())->countAllResults(),
            'aktif'    => (new UserModel())->where('status_akun', 'aktif')->countAllResults(),
            'nonaktif' => (new UserModel())->where('status_akun', 'nonaktif')->countAllResults(),
            'admin'    => (new UserModel())->where('role', 'admin')->countAllResults(),
            'user'     => (new UserModel())->where('role !=', 'admin')->countAllResults(), // sisanya (masyarakat)
        ];

        return view('admin/pages/users', [
            'title'   => 'Manajemen User',
            'stats'   => $stats,
            'rows'    => $rows,
            'pager'   => $pager,
            'filters' => [
                'q'      => $q,
                'role'   => $role,
                'active' => $active,
            ],
            'userName' => session('nama') ?: 'Admin',
            'userRole' => session('role') ?: 'admin',
        ]);
    }

    public function create()
    {
        return view('admin/pages/users_form', [
            'title' => 'Tambah User',
            'mode'  => 'create',
            'row'   => [], // penting biar view aman
            'userName' => session('nama') ?: 'Admin',
            'userRole' => session('role') ?: 'admin',
        ]);
    }

    public function store()
    {
        $rules = [
            'nama'       => 'required|min_length[3]',
            'email'      => 'required|valid_email|is_unique[users.email]',
            'no_hp'      => 'permit_empty|max_length[20]',
            'role'       => 'required|in_list[admin,masyarakat,user]',
            'status_akun'=> 'required|in_list[aktif,nonaktif]',
            'password'   => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('login_error', $this->validator->listErrors());
        }

        $userModel = new UserModel();

        $userModel->insert([
            'nama'          => (string) $this->request->getPost('nama'),
            'email'         => (string) $this->request->getPost('email'),
            'no_hp'         => (string) $this->request->getPost('no_hp'),
            'role'          => (string) $this->request->getPost('role'),
            'status_akun'   => (string) $this->request->getPost('status_akun'),
            'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
        ]);

        return redirect()->to(site_url('admin/users'))
            ->with('login_success', 'User berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $userModel = new UserModel();
        $row = $userModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('admin/users'))
                ->with('login_error', 'User tidak ditemukan.');
        }

        return view('admin/pages/users_form', [
            'title' => 'Edit User',
            'mode'  => 'edit',
            'row'   => $row, // ✅ ini yang sebelumnya kamu tidak kirim
            'userName' => session('nama') ?: 'Admin',
            'userRole' => session('role') ?: 'admin',
        ]);
    }

    public function update(int $id)
    {
        $userModel = new UserModel();
        $row = $userModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('admin/users'))
                ->with('login_error', 'User tidak ditemukan.');
        }

        $rules = [
            'nama'       => 'required|min_length[3]',
            'email'      => "required|valid_email|is_unique[users.email,id,{$id}]",
            'no_hp'      => 'permit_empty|max_length[20]',
            'role'       => 'required|in_list[admin,masyarakat,user]',
            'status_akun'=> 'required|in_list[aktif,nonaktif]',
            'password'   => 'permit_empty|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('login_error', $this->validator->listErrors());
        }

        $data = [
            'nama'        => (string) $this->request->getPost('nama'),
            'email'       => (string) $this->request->getPost('email'),
            'no_hp'       => (string) $this->request->getPost('no_hp'),
            'role'        => (string) $this->request->getPost('role'),
            'status_akun' => (string) $this->request->getPost('status_akun'),
        ];

        $newPass = (string) $this->request->getPost('password');
        if ($newPass !== '') {
            $data['password_hash'] = password_hash($newPass, PASSWORD_DEFAULT);
        }

        $userModel->update($id, $data);

        return redirect()->to(site_url('admin/users'))
            ->with('login_success', 'User berhasil diperbarui.');
    }

    public function toggle(int $id)
    {
        $userModel = new UserModel();
        $row = $userModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('admin/users'))
                ->with('login_error', 'User tidak ditemukan.');
        }

        $next = (($row['status_akun'] ?? 'aktif') === 'aktif') ? 'nonaktif' : 'aktif';
        $userModel->update($id, ['status_akun' => $next]);

        return redirect()->to(site_url('admin/users'))
            ->with('login_success', 'Status user berhasil diubah.');
    }
}
