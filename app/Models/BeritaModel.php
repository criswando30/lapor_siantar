<?php

namespace App\Models;

use CodeIgniter\Model;

class BeritaModel extends Model
{
    protected $table = 'berita';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'judul',
        'slug',
        'ringkas',
        'isi',
        'gambar',
        'status',           // draft | publish
        'tanggal_publish',
    ];

    // =========================
    // Helper: slug
    // =========================
    public function makeSlug(string $judul): string
    {
        helper('text');
        $slug = url_title($judul, '-', true);
        return $slug ?: 'berita';
    }

    /**
     * ✅ FIX: jangan pakai $this->where() berulang karena builder bisa menumpuk.
     * Gunakan query builder baru dari db->table() supaya selalu bersih.
     */
    public function uniqueSlug(string $baseSlug, ?int $ignoreId = null): string
    {
        $slug = $baseSlug ?: 'berita';
        $i = 2;

        while (true) {
            $qb = $this->db->table($this->table)
                ->select('id')
                ->where('slug', $slug);

            if ($ignoreId !== null) {
                $qb->where('id !=', $ignoreId);
            }

            $exists = $qb->get()->getRowArray();
            if (!$exists) {
                return $slug;
            }

            $slug = $baseSlug . '-' . $i;
            $i++;
        }
    }

    // =========================
    // Admin list + filter (UNTUK PAGINATE)
    // =========================
    public function getAdminList(array $filters = []): self
    {
        $q = trim((string) ($filters['q'] ?? ''));
        $status = (string) ($filters['status'] ?? '');
        $from = (string) ($filters['from'] ?? '');
        $to = (string) ($filters['to'] ?? '');

        // ✅ jangan pakai from() / alias
        $this->select([
            'id',
            'judul',
            'slug',
            'ringkas',
            'gambar',
            'status',
            'tanggal_publish',
            'created_at'
        ])
            ->orderBy('created_at', 'DESC');

        if ($q !== '') {
            $this->groupStart()
                ->like('judul', $q)
                ->orLike('ringkas', $q)
                ->groupEnd();
        }

        if ($status !== '') {
            $this->where('status', $status);
        }

        if ($from !== '')
            $this->where('DATE(created_at) >=', $from);
        if ($to !== '')
            $this->where('DATE(created_at) <=', $to);

        return $this;
    }
    // =========================
    // Public list (berita user)
    // =========================
    public function getPublicList(string $q = ''): self
    {
        $q = trim($q);

        // ✅ jangan pakai from() / alias
        $this->select([
            'id',
            'judul',
            'slug',
            'ringkas',
            'gambar',
            'tanggal_publish',
            'created_at'
        ])
            ->where('status', 'publish')
            ->orderBy('tanggal_publish', 'DESC')
            ->orderBy('created_at', 'DESC');

        if ($q !== '') {
            $this->groupStart()
                ->like('judul', $q)
                ->orLike('ringkas', $q)
                ->groupEnd();
        }

        return $this;
    }


    // =========================
    // Public detail by slug
    // =========================
    public function getPublicBySlug(string $slug): ?array
    {
        // ✅ jangan pakai builder()->from alias
        return $this->where('slug', $slug)
            ->where('status', 'publish')
            ->first();
    }

    // =========================
// Public latest (untuk Home)
// =========================
    public function getLatestPublic(int $limit = 6): array
    {
        return $this->select([
            'id',
            'judul',
            'slug',
            'ringkas',
            'gambar',
            'tanggal_publish',
            'created_at'
        ])
            ->where('status', 'publish')
            ->orderBy('tanggal_publish', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

}
