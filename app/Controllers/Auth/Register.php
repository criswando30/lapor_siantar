<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Register extends BaseController
{
    public function index()
    {
        // form register
        return view('user/pages/register', [
            'title' => 'Daftar - LaporSiantar',
        ]);
    }

    public function store()
    {
        $rules = [
            'nik' => 'permit_empty|max_length[32]',
            'nama_lengkap' => 'required|max_length[150]',
            'tempat_tinggal' => 'required|max_length[255]',
            'tanggal_lahir' => 'required|valid_date',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'no_telp' => 'required|max_length[25]|is_unique[users.no_telp]',
            'email' => 'required|valid_email|max_length[150]|is_unique[users.email]',
            'username' => 'required|min_length[4]|max_length[50]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }

        $userModel = new UserModel();

        $data = [
            'nik' => $this->request->getPost('nik') ?: null,
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'tempat_tinggal' => $this->request->getPost('tempat_tinggal'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'no_telp' => $this->request->getPost('no_telp'),
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            // pastikan default role user
            'role' => 'user',
        ];

        try {
            $userModel->insert($data);
        } catch (\Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->with('register_error', 'Registrasi gagal. Coba lagi.');
        }

        return redirect()->to(site_url('register'))
            ->with('register_success', 'Registrasi berhasil. Silakan login.');
    }
}
