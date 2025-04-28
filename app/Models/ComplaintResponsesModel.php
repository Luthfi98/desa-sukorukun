<?php

namespace App\Models;

use CodeIgniter\Model;

class ComplaintResponsesModel extends Model
{
    protected $table = 'complaint_responses';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'complaint_id',
        'user_id',
        'response',
        'created_at'
    ];
    
    // Get responses for a specific complaint
    public function getComplaintResponses($complaintId)
    {
        return $this->select('complaint_responses.*, users.name as user_name')
            ->join('users', 'users.id = complaint_responses.user_id')
            ->where('complaint_id', $complaintId)
            ->orderBy('created_at', 'ASC')
            ->findAll();
    }
    
    // Get the latest response for a complaint
    public function getLatestResponse($complaintId)
    {
        return $this->where('complaint_id', $complaintId)
            ->orderBy('created_at', 'DESC')
            ->first();
    }
    
    // Delete all responses for a complaint
    public function deleteComplaintResponses($complaintId)
    {
        return $this->where('complaint_id', $complaintId)->delete();
    }
} 