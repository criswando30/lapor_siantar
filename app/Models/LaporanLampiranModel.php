<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanLampiranModel extends Model
{
    protected $table      = 'laporan_lampiran';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'laporan_id',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
    ];

    protected $useTimestamps = false; // created_at sudah default CURRENT_TIMESTAMP
}
