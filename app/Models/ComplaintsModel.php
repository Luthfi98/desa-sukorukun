<?php

namespace App\Models;

use CodeIgniter\Model;

class ComplaintsModel extends Model
{
    protected $table            = 'complaints';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'subject', 'description', 'attachment', 'status', 
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get all complaints with user information
     */
    public function getAllComplaints()
    {
        return $this->select('complaints.*, users.name as user_name, residents.name as resident_name')
            ->join('users', 'users.id = complaints.user_id')
            ->join('residents', 'residents.user_id = complaints.user_id')
            ->orderBy('complaints.created_at', 'DESC')
            ->findAll();
    }


    public function getWithRelations()
    {
        $complaints = $this->findAll();
        $residentModel = new ResidentModel();
        $userModel = new UserModel();
        
        foreach ($complaints as &$complaint) {
            // Get resident information
            $resident = $residentModel->where('user_id', $complaint['user_id'])->first();
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
     * Get complaints by status
     */
    public function getComplaintsByStatus($status)
    {
        return $this->select('complaints.*, users.name as user_name, residents.name as resident_name')
            ->join('users', 'users.id = complaints.user_id')
            ->join('residents', 'residents.user_id = complaints.user_id')
            ->where('complaints.status', $status)
            ->orderBy('complaints.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get complaints by user
     */
    public function getUserComplaints($userId)
    {
        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get complaint detail with user information
     */
    public function getComplaintDetail($id)
    {
        return $this->select('complaints.*, users.name as user_name, residents.name as resident_name')
            ->join('users', 'users.id = complaints.user_id')
            ->join('residents', 'residents.user_id = complaints.user_id')
            ->where('complaints.id', $id)
            ->first();
    }

    /**
     * Update complaint status
     */
    public function updateStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }

    // Get stats for admin dashboard
    public function getComplaintStats()
    {
        $total = $this->countAll();
        $pending = $this->where('status', 'pending')->countAllResults();
        $processing = $this->where('status', 'processing')->countAllResults();
        $completed = $this->where('status', 'completed')->countAllResults();
        $rejected = $this->where('status', 'rejected')->countAllResults();

        return [
            'total' => $total,
            'pending' => $pending,
            'processing' => $processing,
            'completed' => $completed,
            'rejected' => $rejected
        ];
    }

    public function countByStatus(string $status)
    {
        return $this->where('status', $status)
                    ->countAllResults();
    }
} 