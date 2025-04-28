<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ComplaintModel;
use App\Models\ResidentModel;

class Complaint extends BaseController
{
    protected $complaintModel;
    protected $residentModel;
    protected $session;

    public function __construct()
    {
        $this->complaintModel = new ComplaintModel();
        $this->residentModel = new ResidentModel();
        $this->session = session();
    }

    public function index()
    {
        $user = $this->session->get('user');
        $data = [
            'title' => 'Daftar Pengaduan',
            'active' => 'complaints'
        ];

        if ($user['role'] === 'admin') {
            // Admin sees all complaints
            $status = $this->request->getGet('status') ?? 'pending';
            
            if ($status != 'all') {
                $complaints = $this->complaintModel->getComplaintsWithResidents(['status' => $status]);
            } else {
                $complaints = $this->complaintModel->getComplaintsWithResidents();
            }
            
            // Get counts for each status
            $data['countPending'] = $this->complaintModel->where('status', 'pending')->countAllResults();
            $data['countProcessing'] = $this->complaintModel->where('status', 'processing')->countAllResults();
            $data['countResolved'] = $this->complaintModel->where('status', 'resolved')->countAllResults();
            $data['countRejected'] = $this->complaintModel->where('status', 'rejected')->countAllResults();
            
            $data['complaints'] = $complaints;
            $data['status'] = $status;
            
            return view('complaints/admin_index', $data);
        } else {
            // Regular user sees only their complaints
            $resident = $this->residentModel->where('user_id', $user['id'])->first();
            if (!$resident) {
                $this->session->setFlashdata('error', 'Data penduduk tidak ditemukan');
                return redirect()->to('/dashboard');
            }
            
            $data['complaints'] = $this->complaintModel->getComplaintsWithResidents(['resident_id' => $resident['id']]);
            $data['resident_id'] = $resident['id'];
            return view('complaints/user_index', $data);
        }
    }

    public function create()
    {
        $user = $this->session->get('user');
        if ($user['role'] === 'admin') {
            return redirect()->to('/complaints');
        }

        $resident = $this->residentModel->where('user_id', $user['id'])->first();
        if (!$resident) {
            $this->session->setFlashdata('error', 'Data penduduk tidak ditemukan');
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Buat Pengaduan Baru',
            'active' => 'complaints',
            'resident_id' => $resident['id']
        ];

        return view('complaints/create', $data);
    }

    public function store()
    {
        $user = $this->session->get('user');
        if ($user['role'] === 'admin') {
            return redirect()->to('/complaints');
        }

        $resident = $this->residentModel->where('user_id', $user['id'])->first();
        if (!$resident) {
            $this->session->setFlashdata('error', 'Data penduduk tidak ditemukan');
            return redirect()->to('/dashboard');
        }

        $rules = [
            'subject' => 'required|min_length[5]|max_length[100]',
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

        $data = [
            'resident_id' => $resident['id'],
            'subject' => $this->request->getPost('subject'),
            'description' => $this->request->getPost('description'),
            'attachment' => $attachment,
            'status' => 'pending'
        ];

        $this->complaintModel->save($data);
        $this->session->setFlashdata('success', 'Pengaduan berhasil dikirim');
        
        return redirect()->to('/complaints');
    }

    public function show($id)
    {
        $user = $this->session->get('user');
        $complaint = $this->complaintModel->find($id);
        
        if (!$complaint) {
            $this->session->setFlashdata('error', 'Pengaduan tidak ditemukan');
            return redirect()->to('/complaints');
        }

        // Check if user is authorized to view this complaint
        if ($user['role'] !== 'admin') {
            $resident = $this->residentModel->where('user_id', $user['id'])->first();
            if (!$resident || $resident['id'] != $complaint['resident_id']) {
                $this->session->setFlashdata('error', 'Anda tidak memiliki akses');
                return redirect()->to('/complaints');
            }
        }

        $data = [
            'title' => 'Detail Pengaduan',
            'active' => 'complaints',
            'complaint' => $complaint,
            'resident' => $this->residentModel->find($complaint['resident_id'])
        ];

        return view('complaints/show', $data);
    }

    public function respond($id)
    {
        $user = $this->session->get('user');
        if ($user['role'] !== 'admin') {
            $this->session->setFlashdata('error', 'Anda tidak memiliki akses');
            return redirect()->to('/complaints');
        }

        $complaint = $this->complaintModel->find($id);
        if (!$complaint) {
            $this->session->setFlashdata('error', 'Pengaduan tidak ditemukan');
            return redirect()->to('/complaints');
        }

        $data = [
            'title' => 'Tanggapi Pengaduan',
            'active' => 'complaints',
            'complaint' => $complaint,
            'resident' => $this->residentModel->find($complaint['resident_id'])
        ];

        return view('complaints/respond', $data);
    }

    public function updateResponse($id)
    {
        $user = $this->session->get('user');
        if ($user['role'] !== 'admin') {
            $this->session->setFlashdata('error', 'Anda tidak memiliki akses');
            return redirect()->to('/complaints');
        }

        $complaint = $this->complaintModel->find($id);
        if (!$complaint) {
            $this->session->setFlashdata('error', 'Pengaduan tidak ditemukan');
            return redirect()->to('/complaints');
        }

        $rules = [
            'response' => 'required|min_length[5]',
            'status' => 'required|in_list[processing,resolved,rejected]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'response' => $this->request->getPost('response'),
            'status' => $this->request->getPost('status'),
            'responded_by' => $user['id']
        ];

        $this->complaintModel->respondToComplaint($id, $data);
        $this->session->setFlashdata('success', 'Tanggapan berhasil disimpan');
        
        return redirect()->to('/complaints');
    }

    /**
     * Download complaint attachment
     */
    public function download($id)
    {
        $complaint = $this->complaintModel->find($id);
        
        // Check if complaint exists and has attachment
        if (!$complaint || !$complaint['attachment']) {
            $this->session->setFlashdata('error', 'Lampiran tidak ditemukan');
            return redirect()->to('/complaints');
        }
        
        // Check if user has permission to download
        if ($this->session->get('user')['role'] !== 'admin' && $complaint['resident_id'] != $this->session->get('user')['id']) {
            $this->session->setFlashdata('error', 'Anda tidak memiliki akses untuk mengunduh lampiran ini');
            return redirect()->to('/complaints');
        }
        
        $filePath = ROOTPATH . 'public/uploads/complaints/' . $complaint['attachment'];
        
        if (!file_exists($filePath)) {
            $this->session->setFlashdata('error', 'File tidak ditemukan');
            return redirect()->to('/complaints');
        }
        
        return $this->response->download($filePath, null);
    }

    public function delete($id)
    {
        $complaint = $this->complaintModel->find($id);
        
        // Check if complaint exists
        if (!$complaint) {
            $this->session->setFlashdata('error', 'Pengaduan tidak ditemukan');
            return redirect()->to('/complaints');
        }
        
        // Check if user has permission to delete
        if ($this->session->get('user')['role'] !== 'admin' && $complaint['resident_id'] != $this->session->get('user')['id']) {
            $this->session->setFlashdata('error', 'Anda tidak memiliki akses untuk menghapus pengaduan ini');
            return redirect()->to('/complaints');
        }
        
        // Check if complaint can be deleted (only pending complaints can be deleted by users)
        if ($this->session->get('user')['role'] !== 'admin' && $complaint['status'] != 'pending') {
            $this->session->setFlashdata('error', 'Pengaduan yang sudah diproses tidak dapat dihapus');
            return redirect()->to('/complaints');
        }
        
        // Delete attachment if exists
        if ($complaint['attachment']) {
            $filePath = ROOTPATH . 'public/uploads/complaints/' . $complaint['attachment'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        // Delete the complaint
        $this->complaintModel->delete($id);
        
        // Redirect based on user role
        if ($this->session->get('user')['role'] === 'admin') {
            return redirect()->to('/complaints/admin')->with('success', 'Pengaduan berhasil dihapus');
        } else {
            return redirect()->to('/complaints')->with('success', 'Pengaduan berhasil dihapus');
        }
    }
} 