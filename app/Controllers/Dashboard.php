<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ResidentModel;
use App\Models\LetterRequestModel;
use App\Models\NotificationModel;
use App\Models\ComplaintModel;

class Dashboard extends BaseController
{
    protected $userModel;
    protected $residentModel;
    protected $letterRequestModel;
    protected $notificationModel;
    protected $complaintModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->residentModel = new ResidentModel();
        $this->letterRequestModel = new LetterRequestModel();
        $this->notificationModel = new NotificationModel();
        $this->complaintModel = new ComplaintModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $userId = $this->session->get('id');
        $userRole = $this->session->get('role');
        
        // Common data for all dashboards
        $data = [
            'title' => 'Dashboard',
            'user' => $this->userModel->find($userId),
            'unreadCount' => $this->notificationModel->getUnreadCount($userId),
        ];
        
        // Different dashboard based on role
        if ($userRole === 'admin' || $userRole === 'staff') {
            // Admin dashboard data
            $data['pendingCount'] = $this->letterRequestModel->countByStatus('pending');
            $data['processingCount'] = $this->letterRequestModel->countByStatus('processing');
            $data['pendingSuratCount'] = $data['pendingCount']; // For notification badge in sidebar
            $data['completedCount'] = $this->letterRequestModel->countByStatus('completed');
            $data['pendingPengaduanCount'] = $this->complaintModel->countByStatus('pending');
            $data['residentCount'] = $this->residentModel->countAllResults();
            
            // Get recent requests and complaints
            $data['recentRequests'] = $this->letterRequestModel->orderBy('created_at', 'DESC')->limit(5)->getWithRelations();
            $data['recentComplaints'] = $this->complaintModel->orderBy('created_at', 'DESC')->limit(5)->getWithRelations();
            
            return view('dashboard/admin', $data);
        } else {
            // Resident dashboard data
            $resident = $this->residentModel->where('user_id', $userId)->first();
            $residentId = $resident ? $resident['id'] : 0;
            
            // Get counts
            $data['mySuratCount'] = $this->letterRequestModel->where('resident_id', $residentId)->countAllResults();
            $data['myComplaintCount'] = $this->complaintModel->where('user_id', $userId)->countAllResults();
            
            // Get recent submissions
            $data['mySuratRecent'] = $this->letterRequestModel->where('resident_id', $residentId)
                                                            ->orderBy('created_at', 'DESC')
                                                            ->limit(5)
                                                            ->getWithRelations();
            
            $data['myComplaintRecent'] = $this->complaintModel->where('user_id', $userId)
                                                            ->orderBy('created_at', 'DESC')
                                                            ->limit(5)
                                                            ->getWithRelations();
            
            return view('dashboard/resident', $data);
        }
    }
} 