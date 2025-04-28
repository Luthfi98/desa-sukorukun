<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username', 'email', 'password', 'name', 'role', 'status',
        'reset_token', 'reset_token_expires_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'username' => 'required|alpha_numeric_space|min_length[3]|max_length[100]',
        'email'    => 'required|valid_email|max_length[100]',
        'password' => 'min_length[8]',
        'name'     => 'required|alpha_numeric_space|min_length[3]|max_length[100]',
        'role'     => 'required|in_list[admin,staff,resident]',
        'status'   => 'required|in_list[active,inactive]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];
    

    /**
     * Hash the password before storing it
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_BCRYPT);
        }

        return $data;
    }

    /**
     * Verify the password
     */
    public function verifyPassword(string $password, string $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Find a user by username
     */
    public function findByUsername(string $username)
    {
        return $this->where('username', $username)
                    ->first();
    }

    /**
     * Find a user by email
     */
    public function findByEmail(string $email)
    {
        return $this->where('email', $email)
                    ->first();
    }

    /**
     * Find a user by reset token
     */
    public function findByResetToken(string $token)
    {
        return $this->where('reset_token', $token)
                    ->where('reset_token_expires_at >', date('Y-m-d H:i:s'))
                    ->first();
    }

    /**
     * Generate a password reset token
     */
    public function generateResetToken(int $userId)
    {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 day'));

        $this->update($userId, [
            'reset_token' => $token,
            'reset_token_expires_at' => $expiresAt
        ]);

        return $token;
    }

    /**
     * Clear the reset token after password reset
     */
    public function clearResetToken(int $userId)
    {
        return $this->update($userId, [
            'reset_token' => null,
            'reset_token_expires_at' => null
        ]);
    }
} 