<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanModel extends Model
{
    protected $table            = 'laporan';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'kode_tiket',
        'user_id',
        'kategori_id',
        'sub_kategori_id',
        'lokasi',
        'alamat_lengkap',
        'latitude',
        'longitude',
        'deskripsi',
        'tanggal_kejadian',
        'status',
        'tanggal_selesai',
        'created_at',
        'updated_at',
    ];

    /**
     * KPI Admin
     */
    public function getAdminKpi(): array
    {
        $rows = $this->db->table($this->table)
            ->select('status, COUNT(*) AS total')
            ->groupBy('status')
            ->get()
            ->getResultArray();

        $out = [
            'total'       => 0,
            'menunggu'    => 0, // laporan_diterima
            'diverifikasi'=> 0,
            'diproses'    => 0, // dalam_proses
            'selesai'     => 0,
        ];

        foreach ($rows as $r) {
            $st = $r['status'];
            $ct = (int) $r['total'];

            $out['total'] += $ct;

            if ($st === 'laporan_diterima') $out['menunggu'] = $ct;
            if ($st === 'diverifikasi')     $out['diverifikasi'] = $ct;
            if ($st === 'dalam_proses')     $out['diproses'] = $ct;
            if ($st === 'selesai')          $out['selesai'] = $ct;
        }

        return $out;
    }

    /**
     * LIST Admin + Filter (anti-double)
     * return $this agar bisa paginate()
     */
    public function getAdminList(array $filters = []): self
    {
        $q          = trim((string) ($filters['q'] ?? ''));
        $status     = (string) ($filters['status'] ?? '');
        $kategoriId = (int) ($filters['kategori_id'] ?? 0);
        $from       = (string) ($filters['from'] ?? '');
        $to         = (string) ($filters['to'] ?? '');

        // RESET builder dengan aman
        $this->builder($this->table);
        $this->from($this->table . ' l');

        $this->select([
                'l.id AS id',
                'l.kode_tiket AS kode',
                'l.created_at AS tanggal',
                'l.status AS status',
                'l.deskripsi AS deskripsi',

                'u.nama AS pelapor',
                'u.no_hp AS kontak',

                'k.nama_kategori AS kategori',
                'sk.nama_subkategori AS subkategori',
            ])
            ->join('users u', 'u.id = l.user_id', 'left')
            ->join('kategori k', 'k.id = l.kategori_id', 'left')
            ->join('sub_kategori sk', 'sk.id = l.sub_kategori_id', 'left')

            // KUNCI anti double:
            ->groupBy('l.id')

            ->orderBy('l.created_at', 'DESC');

        if ($q !== '') {
            $this->groupStart()
                ->like('l.kode_tiket', $q)
                ->orLike('u.nama', $q)
                ->orLike('u.no_hp', $q)
                ->orLike('l.deskripsi', $q)
                ->groupEnd();
        }

        if ($status !== '') {
            $this->where('l.status', $status);
        }

        if ($kategoriId > 0) {
            $this->where('l.kategori_id', $kategoriId);
        }

        if ($from !== '') {
            $this->where('DATE(l.created_at) >=', $from);
        }

        if ($to !== '') {
            $this->where('DATE(l.created_at) <=', $to);
        }

        return $this;
    }

    /**
     * Untuk dashboard "aktivitas terbaru"
     * (aman, konsisten alias)
     */
    public function withKategoriSub(): self
    {
        $this->builder($this->table);
        $this->from($this->table . ' l');

        $this->select([
                'l.*',
                'k.nama_kategori',
                'sk.nama_subkategori',
            ])
            ->join('kategori k', 'k.id = l.kategori_id', 'left')
            ->join('sub_kategori sk', 'sk.id = l.sub_kategori_id', 'left')
            ->groupBy('l.id');

        return $this;
    }

    /**
     * Detail Pengaduan Admin
     */
    public function getAdminDetail(int $id): ?array
    {
        return $this->db->table($this->table . ' l')
            ->select([
                'l.*',
                'u.nama AS pelapor',
                'u.no_hp AS kontak',
                'u.email AS email',
                'k.nama_kategori AS kategori',
                'sk.nama_subkategori AS subkategori',
            ])
            ->join('users u', 'u.id = l.user_id', 'left')
            ->join('kategori k', 'k.id = l.kategori_id', 'left')
            ->join('sub_kategori sk', 'sk.id = l.sub_kategori_id', 'left')
            ->where('l.id', $id)
            ->get()
            ->getRowArray();
    }
}
