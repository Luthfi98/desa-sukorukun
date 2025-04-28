<?php

namespace App\Controllers;

use App\Models\ComplaintsModel;
use App\Models\ComplaintResponsesModel;
use App\Models\ResidentModel;
use App\Models\NotificationModel;

class Complaints extends BaseController
{
    protected $complaintsModel;
    protected $responsesModel;
    protected $residentModel;
    protected $notificationModel;

    public function __construct()
    {
        $this->complaintsModel = new ComplaintsModel();
        $this->responsesModel = new ComplaintResponsesModel();
        $this->residentModel = new ResidentModel();
        $this->notificationModel = new NotificationModel();
    }

    /**
     * Display list of complaints for the current user
     */
    public function index()
    {
        $data = [
            'title' => 'Daftar Pengaduan',
            'active' => 'complaints'
        ];

        if (session()->get('role') === 'admin') {
            // Admin sees all complaints
            $status = $this->request->getGet('status') ?? 'pending';
            
            if ($status != 'all') {
                $complaints = $this->complaintsModel->getComplaintsByStatus($status);
            } else {
                $complaints = $this->complaintsModel->getAllComplaints();
            }
            
            // Get counts for each status
            $data['countPending'] = $this->complaintsModel->where('status', 'pending')->countAllResults();
            $data['countProcessing'] = $this->complaintsModel->where('status', 'processing')->countAllResults();
            $data['countResolved'] = $this->complaintsModel->where('status', 'resolved')->countAllResults();
            $data['countRejected'] = $this->complaintsModel->where('status', 'rejected')->countAllResults();
            
            $data['complaints'] = $complaints;
            $data['status'] = $status;
            
            return view('complaints/admin_index', $data);
        } else {
            // Regular user sees only their complaints
            $resident = $this->residentModel->where('user_id', session()->get('user_id'))->first();
            if (!$resident) {
                return redirect()->to(base_url('dashboard'))->with('error', 'Data penduduk tidak ditemukan');
            }
            
            $data['complaints'] = $this->complaintsModel->getUserComplaints(session()->get('user_id'));
            $data['resident_id'] = $resident['id'];
            return view('complaints/user_index', $data);
        }
    }

    /**
     * Display list of complaints for admin
     */
    public function admin()
    {
        // Check if user is admin
        if (session()->get('role') != 'admin') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $status = $this->request->getGet('status') ?? 'pending';
        
        $data = [
            'title' => 'Manajemen Pengaduan',
            'active' => 'complaints'
        ];
        
        if ($status != 'all') {
            $data['complaints'] = $this->complaintsModel->getComplaintsByStatus($status);
        } else {
            $data['complaints'] = $this->complaintsModel->getAllComplaints();
        }
        
        // Get counts for each status
        $data['countPending'] = $this->complaintsModel->where('status', 'pending')->countAllResults();
        $data['countProcessing'] = $this->complaintsModel->where('status', 'processing')->countAllResults();
        $data['countResolved'] = $this->complaintsModel->where('status', 'resolved')->countAllResults();
        $data['countRejected'] = $this->complaintsModel->where('status', 'rejected')->countAllResults();
        
        $data['status'] = $status;
        
        return view('complaints/admin_index', $data);
    }

    /**
     * Display form to create a new complaint
     */
    public function create()
    {
        // Check if user is resident
        if (session()->get('role') !== 'resident') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $data = [
            'title' => 'Buat Pengaduan Baru',
            'active' => 'complaints',
            'validation' => \Config\Services::validation()
        ];
        
        return view('complaints/create', $data);
    }

    /**
     * Process complaint submission
     */
    public function store()
    {
        // Check if user is resident
        if (session()->get('role') !== 'resident') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Validate input
        $rules = [
            'subject' => 'required|min_length[5]|max_length[255]',
            'description' => 'required|min_length[10]',
            'attachment' => 'permit_empty|uploaded[attachment]|max_size[attachment,2048]|ext_in[attachment,jpg,jpeg,png,pdf]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Handle file upload
        $attachment = null;
        $file = $this->request->getFile('attachment');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/complaints', $newName);
            $attachment = $newName;
        }
        
        // Get resident data
        $resident = $this->residentModel->where('user_id', session()->get('user_id'))->first();
        
        if (!$resident) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Data penduduk tidak ditemukan');
        }
        
        // Insert data
        $data = [
            'user_id' => session()->get('user_id'),
            'subject' => $this->request->getPost('subject'),
            'description' => $this->request->getPost('description'),
            'attachment' => $attachment,
            'status' => 'pending'
        ];
        
        $this->complaintsModel->insert($data);
        
        // Send notification to admin
        $this->notificationModel->insert([
            'user_id' => 1, // Assuming admin has user_id = 1
            'title' => 'Pengaduan Baru',
            'message' => 'Pengaduan baru telah dikirim oleh ' . $resident['name'],
            'link' => 'complaints/admin'
        ]);
        
        return redirect()->to(base_url('complaints'))->with('success', 'Pengaduan berhasil dibuat');
    }

    /**
     * Show complaint details
     */
    public function show($id)
    {
        $complaint = $this->complaintsModel->find($id);
        
        // Check if complaint exists
        if (!$complaint) {
            return redirect()->to(base_url('complaints'))->with('error', 'Pengaduan tidak ditemukan');
        }
        
        // Check if user has permission to view
        if (session()->get('role') !== 'admin' && $complaint['user_id'] != session()->get('user_id')) {
            return redirect()->to(base_url('complaints'))->with('error', 'Anda tidak memiliki akses untuk melihat pengaduan ini');
        }
        
        $resident = $this->residentModel->join('users', 'users.id = residents.user_id')->where('user_id', $complaint['user_id'])->first();
        
        $data = [
            'title' => 'Detail Pengaduan',
            'active' => 'complaints',
            'complaint' => $complaint,
            'resident' => $resident,
            'responses' => $this->responsesModel->getComplaintResponses($id)
        ];
        
        return view('complaints/view', $data);
    }

    /**
     * Display form to respond to a complaint
     */
    public function respond($id)
    {
        // Check if user is admin
        if (session()->get('role') != 'admin') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses untuk menanggapi pengaduan');
        }
        
        $complaint = $this->complaintsModel->find($id);
        
        // Check if complaint exists
        if (!$complaint) {
            return redirect()->to(base_url('complaints/admin'))->with('error', 'Pengaduan tidak ditemukan');
        }
        
        // Check if complaint is still pending
        if ($complaint['status'] != 'pending') {
            return redirect()->to(base_url('complaints/admin'))->with('error', 'Pengaduan ini sudah ditanggapi');
        }
        
        $resident = $this->residentModel->join('users', 'users.id = residents.user_id')->where('user_id', $complaint['user_id'])->first();
        
        $data = [
            'title' => 'Tanggapi Pengaduan',
            'active' => 'complaints',
            'complaint' => $complaint,
            'resident' => $resident
        ];
        
        return view('complaints/respond', $data);
    }

    /**
     * Display form to process a complaint
     */
    public function process($id)
    {
        // Check if user is admin
        if (session()->get('role') != 'admin') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses untuk memproses pengaduan');
        }
        
        $complaint = $this->complaintsModel->find($id);
        
        // Check if complaint exists
        if (!$complaint) {
            return redirect()->to(base_url('complaints/admin'))->with('error', 'Pengaduan tidak ditemukan');
        }
        
        // Check if complaint is still pending
        if ($complaint['status'] != 'pending') {
            return redirect()->to(base_url('complaints/admin'))->with('error', 'Pengaduan ini sudah diproses sebelumnya');
        }
        
        $resident = $this->residentModel->join('users', 'users.id = residents.user_id')->where('user_id', $complaint['user_id'])->first();
        
        $data = [
            'title' => 'Proses Pengaduan',
            'active' => 'complaints',
            'complaint' => $complaint,
            'resident' => $resident
        ];
        
        return view('complaints/process', $data);
    }

    /**
     * Process response submission
     */
    public function updateResponse($id)
    {
        // Check if user is admin
        if (session()->get('role') != 'admin') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses untuk menanggapi pengaduan');
        }
        
        // Validate input
        $rules = [
            'response' => 'required|min_length[5]',
            'status' => 'required|in_list[processing,resolved,rejected]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $complaint = $this->complaintsModel->find($id);
        
        // Check if complaint exists
        if (!$complaint) {
            return redirect()->to(base_url('complaints/admin'))->with('error', 'Pengaduan tidak ditemukan');
        }
        
        // Update complaint with response
        $this->complaintsModel->update($id, [
            'response' => $this->request->getPost('response'),
            'status' => $this->request->getPost('status'),
            'responded_by' => session()->get('user_id')
        ]);
        
        // Add entry to complaint_responses table
        $this->responsesModel->insert([
            'complaint_id' => $id,
            'user_id' => session()->get('user_id'),
            'response' => $this->request->getPost('response')
        ]);
        
        // Send notification to user
        $this->notificationModel->insert([
            'user_id' => $complaint['user_id'],
            'title' => 'Tanggapan Pengaduan',
            'message' => 'Pengaduan Anda telah ditanggapi oleh admin',
            'link' => 'complaints/' . $id
        ]);
        
        return redirect()->to(base_url('complaints/admin'))->with('success', 'Tanggapan berhasil dikirim');
    }

    /**
     * Delete a complaint
     */
    public function delete($id)
    {
        $complaint = $this->complaintsModel->find($id);
        
        // Check if complaint exists
        if (!$complaint) {
            return redirect()->to(base_url('complaints'))->with('error', 'Pengaduan tidak ditemukan');
        }
        
        // Check if user has permission to delete
        if (session()->get('role') !== 'admin' && $complaint['user_id'] != session()->get('user_id')) {
            return redirect()->to(base_url('complaints'))->with('error', 'Anda tidak memiliki akses untuk menghapus pengaduan ini');
        }
        
        // Check if complaint can be deleted (only pending complaints can be deleted by users)
        if (session()->get('role') !== 'admin' && $complaint['status'] != 'pending') {
            return redirect()->to(base_url('complaints'))->with('error', 'Pengaduan yang sudah diproses tidak dapat dihapus');
        }
        
        // Delete attachment if exists
        if ($complaint['attachment']) {
            $filePath = ROOTPATH . 'public/uploads/complaints/' . $complaint['attachment'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        // Delete the complaint
        $this->complaintsModel->delete($id);
        
        // Redirect based on user role
        if (session()->get('role') === 'admin') {
            return redirect()->to(base_url('complaints/admin'))->with('success', 'Pengaduan berhasil dihapus');
        } else {
            return redirect()->to(base_url('complaints'))->with('success', 'Pengaduan berhasil dihapus');
        }
    }
    
    /**
     * Download complaint attachment
     */
    public function download($id)
    {
        $complaint = $this->complaintsModel->find($id);
        
        // Check if complaint exists and has attachment
        if (!$complaint || !$complaint['attachment']) {
            return redirect()->to(base_url('complaints'))->with('error', 'Lampiran tidak ditemukan');
        }
        
        // Check if user has permission to download
        if (session()->get('role') !== 'admin' && $complaint['user_id'] != session()->get('user_id')) {
            return redirect()->to(base_url('complaints'))->with('error', 'Anda tidak memiliki akses untuk mengunduh lampiran ini');
        }
        
        $filePath = ROOTPATH . 'public/uploads/complaints/' . $complaint['attachment'];
        
        if (!file_exists($filePath)) {
            return redirect()->to(base_url('complaints'))->with('error', 'File tidak ditemukan');
        }
        
        return $this->response->download($filePath, null);
    }
    
    /**
     * Process a complaint - set status to processing
     */
    public function processComplaint($id)
    {
        // Check if user is admin
        if (session()->get('role') != 'admin') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses untuk memproses pengaduan');
        }
        
        $complaint = $this->complaintsModel->find($id);
        
        // Check if complaint exists
        if (!$complaint) {
            return redirect()->to(base_url('complaints/admin'))->with('error', 'Pengaduan tidak ditemukan');
        }
        
        // Check if complaint is still pending
        if ($complaint['status'] != 'pending') {
            return redirect()->to(base_url('complaints/admin'))->with('error', 'Pengaduan ini sudah diproses sebelumnya');
        }
        
        $response = $this->request->getPost('response') ?? 'Pengaduan sedang diproses oleh admin.';
        
        // Update complaint status to processing
        $this->complaintsModel->update($id, [
            'status' => 'processing'
        ]);
        
        // Add entry to complaint_responses table
        $this->responsesModel->insert([
            'complaint_id' => $id,
            'user_id' => session()->get('user_id'),
            'response' => $response
        ]);
        
        // Send notification to user
        $this->notificationModel->insert([
            'user_id' => $complaint['user_id'],
            'title' => 'Pengaduan Diproses',
            'message' => 'Pengaduan Anda sedang diproses oleh admin',
            'link' => 'complaints/' . $id
        ]);
        
        return redirect()->to(base_url('complaints/admin'))->with('success', 'Pengaduan berhasil diproses');
    }
    
    /**
     * Resolve a complaint - set status to resolved
     */
    public function resolve($id)
    {
        // Check if user is admin
        if (session()->get('role') != 'admin') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses untuk menyelesaikan pengaduan');
        }
        
        $complaint = $this->complaintsModel->find($id);
        
        // Check if complaint exists
        if (!$complaint) {
            return redirect()->to(base_url('complaints/' . $id))->with('error', 'Pengaduan tidak ditemukan');
        }
        
        // Check if complaint is in processing status
        if ($complaint['status'] != 'processing') {
            return redirect()->to(base_url('complaints/' . $id))->with('error', 'Hanya pengaduan dengan status "Diproses" yang dapat diselesaikan');
        }
        
        $resolution_notes = $this->request->getPost('response');
        
        // Update complaint status to resolved without changing the original response
        $this->complaintsModel->update($id, [
            'status' => 'resolved'
        ]);
        
        // Add a new entry to complaint_responses table
        $this->responsesModel->insert([
            'complaint_id' => $id,
            'user_id' => session()->get('user_id'),
            'response' => '[SELESAI] ' . $resolution_notes
        ]);
        
        // Send notification to user
        $this->notificationModel->insert([
            'user_id' => $complaint['user_id'],
            'title' => 'Pengaduan Selesai',
            'message' => 'Pengaduan Anda telah selesai ditangani',
            'link' => 'complaints/' . $id
        ]);
        
        return redirect()->to(base_url('complaints/' . $id))->with('success', 'Pengaduan berhasil diselesaikan');
    }

    /**
     * Get DataTable data for complaints
     */
    public function getDataTable()
    {
        $status = $this->request->getGet('status');
        $dateStart = $this->request->getGet('date_start');
        $dateEnd = $this->request->getGet('date_end');
        
        // Check if user is admin
        if (session()->get('role') === 'admin') {
            if ($status && $status !== 'all') {
                $complaints = $this->complaintsModel->getComplaintsByStatus($status);
            } else {
                $complaints = $this->complaintsModel->getAllComplaints();
            }
        } else {
            // Regular user sees only their complaints
            $complaints = $this->complaintsModel->getUserComplaints(session()->get('user_id'));
        }

        // Apply date filtering if dates are provided
        if ($dateStart && $dateEnd) {
            $dateStart = date('Y-m-d 00:00:00', strtotime($dateStart));
            $dateEnd = date('Y-m-d 23:59:59', strtotime($dateEnd));
            
            $complaints = array_filter($complaints, function($complaint) use ($dateStart, $dateEnd) {
                $complaintDate = strtotime($complaint['created_at']);
                return $complaintDate >= strtotime($dateStart) && $complaintDate <= strtotime($dateEnd);
            });
        }

        $data = [];
        foreach ($complaints as $complaint) {
            $data[] = [
                $complaint['id'], // ID for actions
                $complaint['resident_name'] ?? $complaint['user_name'] ?? 'Unknown', // Nama Pengirim
                $complaint['subject'],
                $complaint['created_at'],
                $complaint['status'],
                $complaint['id'] // ID for actions
            ];
        }

        return $this->response->setJSON([
            'draw' => $this->request->getGet('draw'),
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
            'data' => $data
        ]);
    }
} 