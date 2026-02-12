<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

class Tentang extends BaseController
{
    public function index(): string
    {
        return view('pages/tentang', [
            'title' => 'Tentang - LaporSiantar',
        ]);
    }
}
