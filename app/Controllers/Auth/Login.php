<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Login extends BaseController
{
    public function index()
    {
        // Kalau login kamu modal di home, method ini boleh dipakai untuk halaman login terpisah
        // Kalau tidak dipakai, boleh dihapus.
        return view('pages/login', [
            'title' => 'Login - LaporSiantar',
        ]);
    }

    public function authenticate()
    {
        $rules = [
            'identifier' => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('login_error', 'Identifier dan password wajib diisi.');
        }

        $identifier = trim((string) $this->request->getPost('identifier'));
        $password   = (string) $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->findByIdentifier($identifier);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return redirect()->back()
                ->withInput()
                ->with('login_error', 'Login gagal. Cek kembali data Anda.');
        }

        session()->set([
            'isLoggedIn' => true,
            'user_id'    => $user['id'],
            'username'   => $user['username'],
            'nama'       => $user['nama_lengkap'],
            'email'      => $user['email'],
            'role'       => $user['role'],
        ]);

        if (($user['role'] ?? '') === 'admin') {
            return redirect()->to(site_url('admin/dashboard'))
                ->with('login_success', 'Berhasil login.');
        }

        return redirect()->to(site_url('/'))
            ->with('login_success', 'Berhasil login.');
    }
}
