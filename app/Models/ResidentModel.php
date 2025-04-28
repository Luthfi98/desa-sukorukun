<?php

namespace App\Models;

use CodeIgniter\Model;

class ResidentModel extends Model
{
    protected $table            = 'residents';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nik', 'kk', 'name', 'birth_place', 'birth_date', 'gender', 'address',
        'rt', 'rw', 'village', 'district', 'religion', 'marital_status',
        'occupation', 'nationality', 'education', 'father_name', 'mother_name', 'user_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nik'           => 'required|numeric|exact_length[16]|is_unique[residents.nik,id,{id}]',
        'kk'            => 'required|numeric|exact_length[16]',
        'name'          => 'required|alpha_numeric_space|min_length[3]|max_length[100]',
        'birth_place'   => 'required|alpha_numeric_space|max_length[100]',
        'birth_date'    => 'required|valid_date',
        'gender'        => 'required|in_list[male,female]',
        'address'       => 'required',
        'rt'            => 'required|max_length[5]',
        'rw'            => 'required|max_length[5]',
        'village'       => 'required|max_length[100]',
        'district'      => 'required|max_length[100]',
        'religion'      => 'required|max_length[20]',
        'marital_status'=> 'required|in_list[single,married,divorced,widowed]',
        'occupation'    => 'required|max_length[100]',
        'nationality'   => 'required|max_length[50]',
        'education'     => 'required|max_length[50]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Find a resident by NIK
     */
    public function findByNIK(string $nik)
    {
        return $this->where('nik', $nik)
                    ->first();
    }

    /**
     * Find residents by name (partial search)
     */
    public function findByName(string $name)
    {
        return $this->like('name', $name)
                    ->findAll();
    }

    /**
     * Get a resident with user data
     */
    public function getWithUser(int $id)
    {
        return $this->select('residents.*, users.username, users.email, users.role')
                    ->join('users', 'users.id = residents.user_id', 'left')
                    ->find($id);
    }

    /**
     * Import residents from Excel/CSV data
     */
    public function importFromArray(array $data)
    {
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($data as $row) {
            try {
                // Skip if NIK already exists
                if ($this->findByNIK($row['nik'])) {
                    $errors[] = "NIK {$row['nik']} already exists";
                    $errorCount++;
                    continue;
                }

                $this->insert([
                    'nik'           => $row['nik'],
                    'kk'            => $row['kk'],
                    'name'          => $row['name'],
                    'birth_place'   => $row['birth_place'],
                    'birth_date'    => $row['birth_date'],
                    'gender'        => strtolower($row['gender']),
                    'address'       => $row['address'],
                    'rt'            => $row['rt'],
                    'rw'            => $row['rw'],
                    'village'       => $row['village'],
                    'district'      => $row['district'],
                    'religion'      => $row['religion'],
                    'marital_status'=> strtolower($row['marital_status']),
                    'occupation'    => $row['occupation'],
                    'nationality'   => $row['nationality'] ?? 'WNI',
                    'education'     => $row['education'],
                    'father_name'   => $row['father_name'] ?? null,
                    'mother_name'   => $row['mother_name'] ?? null,
                ]);
                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Error on row for NIK {$row['nik']}: " . $e->getMessage();
                $errorCount++;
            }
        }

        return [
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'errors' => $errors
        ];
    }
} 