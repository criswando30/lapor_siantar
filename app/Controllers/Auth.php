<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function register()
    {
        return view('pages/register', ['title' => 'Daftar']);
    }

    public function store()
    {
        return redirect()->back()->with('register_error', 'Fitur daftar belum diaktifkan.');
    }

    public function login()
    {
        return view('pages/login', ['title' => 'Masuk']);
    }

    public function attempt()
    {
        return redirect()->back()->with('login_error', 'Fitur login belum diaktifkan.');
    }
}
