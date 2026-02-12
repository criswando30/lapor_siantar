<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table      = 'kategori';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    public function getActive(): array
    {
        return $this->where('aktif', 1)->orderBy('nama', 'ASC')->findAll();
    }
}
