<?php

namespace App\Models;

use CodeIgniter\Model;

class SubKategoriModel extends Model
{
    protected $table      = 'sub_kategori';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'kategori_id',
        'nama_subkategori',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // helper: ambil subkategori by kategori
    public function byKategori(int $kategoriId): array
    {
        return $this->select('id, kategori_id, nama_subkategori')
            ->where('kategori_id', $kategoriId)
            ->orderBy('nama_subkategori', 'ASC')
            ->findAll();
    }
}
