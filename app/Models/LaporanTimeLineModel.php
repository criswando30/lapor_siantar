<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanTimelineModel extends Model
{
    protected $table      = 'laporan_timeline';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'laporan_id',
        'status',
        'title',
        'note',
        'icon',
        'state',
        'created_by',
    ];

    protected $useTimestamps = false; // created_at sudah default CURRENT_TIMESTAMP
}
