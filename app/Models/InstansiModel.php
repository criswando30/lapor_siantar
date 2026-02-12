<?php

namespace App\Models;

use CodeIgniter\Model;

class InstansiModel extends Model
{
    protected $table      = 'instansi';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    public function getActive(): array
    {
        return $this->where('aktif', 1)->orderBy('nama', 'ASC')->findAll();
    }
}
