<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Login extends BaseController
{
    public function authenticate()
    {
        $rules = [
            'identifier' => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('login_error', 'Email/No. HP dan password wajib diisi.');
        }

        $identifier = trim((string) $this->request->getPost('identifier'));
        $password = (string) $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('email', $identifier)
            ->orWhere('no_hp', $identifier)
            ->first();


        if (!$user) {
            return redirect()->back()
                ->withInput()
                ->with('login_error', 'Login gagal. Email/No. HP tidak ditemukan.');
        }

        if (($user['status_akun'] ?? 'nonaktif') !== 'aktif') {
            return redirect()->back()
                ->withInput()
                ->with('login_error', 'Akun Anda nonaktif. Silakan hubungi admin.');
        }

        if (!password_verify($password, (string) $user['password_hash'])) {
            return redirect()->back()
                ->withInput()
                ->with('login_error', 'Login gagal. Password salah.');
        }

        session()->set([
            'isLoggedIn' => true,
            'user_id' => (int) $user['id'],
            'nama' => (string) $user['nama'],
            'email' => (string) ($user['email'] ?? ''),
            'no_hp' => (string) $user['no_hp'],
            'role' => (string) $user['role'], // admin / masyarakat
        ]);

        if (($user['role'] ?? '') === 'admin') {
            return redirect()->to(site_url('admin/dashboard'))
                ->with('login_success', 'Berhasil login.');
        }

        return redirect()->to(site_url('/'))
            ->with('login_success', 'Berhasil login.');
    }
}
