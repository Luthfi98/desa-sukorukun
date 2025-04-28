<?php

namespace App\Models;

use CodeIgniter\Model;

class ComplaintModel extends Model
{
    protected $table            = 'complaints';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'resident_id', 'subject', 'description', 'attachment', 'status', 'response', 'responded_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'resident_id' => 'required|numeric',
        'subject'     => 'required|min_length[5]|max_length[100]',
        'description' => 'required|min_length[10]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Count complaints by status
     */
    public function countByStatus(string $status)
    {
        return $this->where('status', $status)
                    ->countAllResults();
    }

    /**
     * Get complaints with related data
     */
    public function getWithRelations()
    {
        $complaints = $this->findAll();
        $residentModel = new ResidentModel();
        $userModel = new UserModel();
        
        foreach ($complaints as &$complaint) {
            // Get resident information
            $resident = $residentModel->find($complaint['user_id']);
            if ($resident) {
                $complaint['resident_name'] = $resident['name'] ?? 'Unknown';
                $complaint['reporter_name'] = $resident['name'] ?? 'Unknown';
            } else {
                $complaint['resident_name'] = 'Unknown';
                $complaint['reporter_name'] = 'Unknown';
            }
            
            // Get responder information if complaint was responded to
            if (!empty($complaint['responded_by'])) {
                $responder = $userModel->find($complaint['responded_by']);
                if ($responder) {
                    $complaint['responder_name'] = $responder['name'] ?? 'Unknown';
                } else {
                    $complaint['responder_name'] = 'Unknown';
                }
            }
        }
        
        return $complaints;
    }

    /**
     * Get complaints with resident information
     *
     * @param array $filters
     * @return array
     */
    public function getComplaintsWithResidents($filters = [])
    {
        $builder = $this->db->table('complaints c');
        $builder->select('c.*, r.name as resident_name, r.nik, u.username as responder_name');
        $builder->join('residents r', 'r.id = c.resident_id');
        $builder->join('users u', 'u.id = c.responded_by', 'left');
        
        if (isset($filters['status'])) {
            $builder->where('c.status', $filters['status']);
        }
        
        if (isset($filters['resident_id'])) {
            $builder->where('c.resident_id', $filters['resident_id']);
        }
        
        return $builder->get()->getResultArray();
    }

    /**
     * Update complaint status and response
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function respondToComplaint($id, $data)
    {
        return $this->update($id, [
            'status'       => $data['status'],
            'response'     => $data['response'],
            'responded_by' => $data['responded_by'],
        ]);
    }
} 