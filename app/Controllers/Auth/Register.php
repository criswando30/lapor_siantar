<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Register extends BaseController
{
    public function index()
    {
        return view('user/pages/register', [
            'title' => 'Daftar - Lapor E-Gov Siantar',
        ]);
    }

    public function store()
    {
        $rules = [
            'nama' => 'required|max_length[100]',
            'no_hp' => 'required|max_length[20]|is_unique[users.no_hp]',
            'email' => 'required|valid_email|max_length[100]|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];


        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }

        $userModel = new UserModel();

        $email = trim((string) $this->request->getPost('email'));


        $data = [
            'nama' => $this->request->getPost('nama'),
            'no_hp' => $this->request->getPost('no_hp'),
            'email' => $email,
            'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'masyarakat',
            'status_akun' => 'aktif',
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
