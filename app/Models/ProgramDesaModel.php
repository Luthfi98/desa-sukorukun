<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgramDesaModel extends Model
{
    protected $table            = 'program_desa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_program', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 
        'status', 'anggaran', 'created_by', 'updated_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Get programs with creator information
    public function getProgramsWithUser()
    {
        $programs = $this->findAll();
        $userModel = new UserModel();
        
        foreach ($programs as &$program) {
            $creator = $userModel->find($program['created_by']);
            if ($creator) {
                $program['creator_name'] = $creator['name'];
            } else {
                $program['creator_name'] = 'Unknown';
            }
            
            if (!empty($program['updated_by'])) {
                $updater = $userModel->find($program['updated_by']);
                if ($updater) {
                    $program['updater_name'] = $updater['name'];
                } else {
                    $program['updater_name'] = 'Unknown';
                }
            }
        }
        
        return $programs;
    }
} 