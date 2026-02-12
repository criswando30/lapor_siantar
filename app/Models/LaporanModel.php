<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanModel extends Model
{
    protected $table      = 'laporan';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'kode',
        'user_id',
        'judul',
        'isi',
        'tanggal_kejadian',
        'lokasi_kejadian',
        'instansi_id',
        'kategori_id',
        'status',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function findDetailByKode(string $kode): ?array
    {
        $row = $this->select('laporan.*, instansi.nama AS instansi, kategori.nama AS kategori')
            ->join('instansi', 'instansi.id = laporan.instansi_id', 'left')
            ->join('kategori', 'kategori.id = laporan.kategori_id', 'left')
            ->where('laporan.kode', $kode)
            ->first();

        return $row ?: null;
    }
}
