<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nik',
        'nama_lengkap',
        'tempat_tinggal',
        'tanggal_lahir',
        'jenis_kelamin',
        'no_telp',
        'email',
        'username',
        'password_hash',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function findByIdentifier(string $identifier)
    {
        return $this->groupStart()
            ->where('email', $identifier)
            ->orWhere('username', $identifier)
            ->orWhere('no_telp', $identifier)
        ->groupEnd()
        ->first();
    }
}
