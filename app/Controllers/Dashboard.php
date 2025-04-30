<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ResidentModel;
use App\Models\LetterRequestModel;
use App\Models\NotificationModel;
use App\Models\ComplaintModel;
use App\Models\DeathCertificateModel;
use App\Models\DomicileRequestModel;
use App\Models\GeneralRequestModel;
use App\Models\HeirRequestModel;
use App\Models\LetterTypeModel;
use App\Models\NewsModel;
use App\Models\RelocationRequestModel;

class Dashboard extends BaseController
{
    protected $userModel;
    protected $residentModel;
    protected $letterRequestModel;
    protected $notificationModel;
    protected $complaintModel;
    protected $deathCertificateModel;
    protected $domicileRequestModel;
    protected $generalRequestModel;
    protected $heirRequestModel;
    protected $letterTypeModel;
    protected $newsModel;
    protected $relocationRequestModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->residentModel = new ResidentModel();
        $this->letterRequestModel = new LetterRequestModel();
        $this->notificationModel = new NotificationModel();
        $this->complaintModel = new ComplaintModel();
        $this->deathCertificateModel = new DeathCertificateModel();
        $this->domicileRequestModel = new DomicileRequestModel();
        $this->generalRequestModel = new GeneralRequestModel();
        $this->heirRequestModel = new HeirRequestModel();
        $this->letterTypeModel = new LetterTypeModel();
        $this->newsModel = new NewsModel();
        $this->relocationRequestModel = new RelocationRequestModel();
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
            
            // Get counts for different types of requests
            $data['deathCertificateCount'] = $this->deathCertificateModel->countAllResults();
            $data['domicileRequestCount'] = $this->domicileRequestModel->countAllResults();
            $data['generalRequestCount'] = $this->generalRequestModel->countAllResults();
            $data['heirRequestCount'] = $this->heirRequestModel->countAllResults();
            $data['newsCount'] = $this->newsModel->countAllResults();
            $data['relocationCount'] = $this->relocationRequestModel->countAllResults();
            
            // Get recent requests and complaints
            $data['recentRequests'] = $this->letterRequestModel->orderBy('created_at', 'DESC')->limit(5)->getWithRelations();
            $data['recentComplaints'] = $this->complaintModel->orderBy('created_at', 'DESC')->limit(5)->getWithRelations();
            
            // Get recent requests by type
            $data['recentDeathCertificates'] = $this->deathCertificateModel->orderBy('created_at', 'DESC')->limit(5)->findAll();
            $data['recentDomicileRequests'] = $this->domicileRequestModel->orderBy('created_at', 'DESC')->limit(5)->findAll();
            $data['recentGeneralRequests'] = $this->generalRequestModel->select('certificate_letters.*, residents.name as resident_name, letter_types.name as request_type')
            ->join('letter_types', 'letter_types.id = certificate_letters.letter_type_id')
            ->join('residents', 'residents.id = certificate_letters.resident_id')->orderBy('created_at', 'DESC')->limit(5)->findAll();
            $data['recentHeirRequests'] = $this->heirRequestModel->orderBy('created_at', 'DESC')->limit(5)->findAll();
            $data['recentNews'] = $this->newsModel->orderBy('created_at', 'DESC')->limit(5)->findAll();
            $data['recentRelocations'] = $this->relocationRequestModel->select('relocation_letters.*, residents.name as resident_name, letter_types.name as request_type')->join('letter_types', 'letter_types.id = relocation_letters.letter_type_id')->join('residents', 'residents.id = relocation_letters.resident_id')->orderBy('created_at', 'DESC')->limit(5)->findAll();
            
            return view('dashboard/admin', $data);
        } else {
            // Resident dashboard data
            $resident = $this->residentModel->where('user_id', $userId)->first();
            $residentId = $resident ? $resident['id'] : 0;
            
            // Get counts for resident's requests
            $data['mySuratCount'] = $this->letterRequestModel->where('resident_id', $residentId)->countAllResults();
            $data['myComplaintCount'] = $this->complaintModel->where('user_id', $userId)->countAllResults();
            $data['myDeathCertificateCount'] = $this->deathCertificateModel->where('created_by', session()->get('user_id'))->countAllResults();
            $data['myDomicileRequestCount'] = $this->domicileRequestModel->where('resident_id', $residentId)->countAllResults();
            $data['myGeneralRequestCount'] = $this->generalRequestModel->where('resident_id', $residentId)->countAllResults();
            $data['myHeirRequestCount'] = $this->heirRequestModel->where('resident_id', $residentId)->countAllResults();
            $data['myRelocationCount'] = $this->relocationRequestModel->where('resident_id', $residentId)->countAllResults();
            
            // Get recent submissions
            $data['mySuratRecent'] = $this->letterRequestModel->where('resident_id', $residentId)
                                                            ->orderBy('created_at', 'DESC')
                                                            ->limit(5)
                                                            ->getWithRelations();
            
            $data['myComplaintRecent'] = $this->complaintModel->where('user_id', $userId)
                                                            ->orderBy('created_at', 'DESC')
                                                            ->limit(5)
                                                            ->getWithRelations();
            
            // Get recent requests by type
            $data['myDeathCertificateRecent'] = $this->deathCertificateModel->where('created_by', session()->get('user_id'))
                                                                          ->orderBy('created_at', 'DESC')
                                                                          ->limit(5)
                                                                          ->findAll();
            
            $data['myDomicileRequestRecent'] = $this->domicileRequestModel->where('resident_id', $residentId)
                                                                        ->orderBy('created_at', 'DESC')
                                                                        ->limit(5)
                                                                        ->findAll();
            
            $data['myGeneralRequestRecent'] = $this->generalRequestModel->where('resident_id', $residentId)
                                                                      ->orderBy('created_at', 'DESC')
                                                                      ->limit(5)
                                                                      ->findAll();
            
            $data['myHeirRequestRecent'] = $this->heirRequestModel->where('resident_id', $residentId)
                                                                ->orderBy('created_at', 'DESC')
                                                                ->limit(5)
                                                                ->findAll();
            
            $data['myRelocationRecent'] = $this->relocationRequestModel->where('resident_id', $residentId)
                                                              ->orderBy('created_at', 'DESC')
                                                              ->limit(5)
                                                              ->findAll();
            
            return view('dashboard/resident', $data);
        }
    }
} 