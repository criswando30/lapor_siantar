<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'nama',
        'no_hp',
        'email',
        'password_hash',
        'role',
        'status_akun',
        'created_at',
        'updated_at',
    ];

    public function findByIdentifier(string $identifier): ?array
    {
        $identifier = trim($identifier);

        return $this->where('email', $identifier)
            ->orWhere('no_hp', $identifier)
            ->first();
    }

}
