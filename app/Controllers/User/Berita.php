<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\BeritaModel;

class Berita extends BaseController
{
    public function index()
    {
        $m = new BeritaModel();
        $q = trim((string) $this->request->getGet('q'));

        $m->getPublicList($q);

        $rows  = $m->paginate(9, 'berita_public');
        $pager = $m->pager;

        return view('user/pages/berita', [
            'title'  => 'Berita',
            'rows'   => $rows,
            'pager'  => $pager,
            'q'      => $q,
        ]);
    }

    public function detail(string $slug)
    {
        $m = new BeritaModel();
        $row = $m->getPublicBySlug($slug);

        if (!$row) {
            return redirect()->to(site_url('berita'))->with('login_error', 'Berita tidak ditemukan.');
        }

        return view('user/pages/berita_detail', [
            'title' => $row['judul'],
            'row'   => $row,
        ]);
    }
}
