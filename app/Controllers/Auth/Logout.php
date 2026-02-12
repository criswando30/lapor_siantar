<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;

class Logout extends BaseController
{
    public function index()
    {
        session()->destroy();

        return redirect()->to(site_url('/'))
            ->with('login_success', 'Anda sudah logout.');
    }
}
