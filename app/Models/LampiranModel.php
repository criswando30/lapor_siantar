<?php

namespace App\Models;

use CodeIgniter\Model;

class LampiranModel extends Model
{
    protected $table      = 'lampiran';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['laporan_id', 'nama_file', 'path_file', 'created_at'];
    public    $useTimestamps = false;
}
