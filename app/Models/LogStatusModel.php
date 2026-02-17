<?php

namespace App\Models;

use CodeIgniter\Model;

class LogStatusModel extends Model
{
    protected $table      = 'log_status';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['laporan_id', 'status', 'keterangan', 'diubah_oleh', 'created_at'];
    public    $useTimestamps = false;
}
