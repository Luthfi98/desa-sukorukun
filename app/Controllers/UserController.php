<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ResidentModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class UserController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\UserModel';
    protected $format    = 'json';
    protected $residentModel;
    protected $session;

    public function __construct()
    {
        $this->model = new UserModel();
        $this->residentModel = new ResidentModel();
        $this->session = \Config\Services::session();

        // Check if user is logged in and is admin
        if (!$this->session->has('user_id') || $this->session->get('role') !== 'admin') {
            return redirect()->to('/auth/login')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
    }

    public function index()
    {
        // Check if user is admin
        if ($this->session->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $data = [
            'title' => 'Manajemen Pengguna'
        ];
        return view('users/index', $data);
    }

    public function show($id = null)
    {
        // Check if user is admin
        if ($this->session->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $user = $this->model->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'Pengguna tidak ditemukan');
        }

        // Get resident data if user is a resident
        $residentData = null;
        if ($user['role'] === 'resident') {
            $residentData = $this->residentModel->where('user_id', $id)->first();
        }

        // Get letter request history from notifications
        $notificationModel = new \App\Models\NotificationModel();
        $letterRequests = $notificationModel->where('user_id', $id)
            ->where('title LIKE', '%Pengajuan Surat%')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Detail Pengguna',
            'user' => $user,
            'resident' => $residentData,
            'letterRequests' => $letterRequests
        ];

        return view('users/show', $data);
    }

    public function create()
    {
        // Check if user is admin
        if ($this->session->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Get residents that don't have user accounts yet
        $available_residents = $this->residentModel->where('user_id IS NULL')->findAll();

        $data = [
            'title' => 'Tambah Data Pengguna',
            'available_residents' => $available_residents
        ];
        return view('users/create', $data);
    }

    public function store()
    {
        // Check if user is admin
        if ($this->session->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'password_confirmation' => 'required|matches[password]',
            'name' => 'required',
            'role' => 'required|in_list[admin,staff,resident]',
            'status' => 'required|in_list[active,inactive]'
        ];

        // Add resident_id validation if role is resident
        if ($this->request->getPost('role') === 'resident') {
            $rules['resident_id'] = 'required|numeric|is_not_unique[residents.id]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'name' => $this->request->getPost('name'),
            'role' => $this->request->getPost('role'),
            'status' => $this->request->getPost('status')?'active':'inactive'
        ];

        // Start database transaction
        $this->model->db->transStart();

        try {
            // Insert user data
            $userId = $this->model->insert($data);

            // If role is resident, update the resident's user_id
            if ($this->request->getPost('role') === 'resident') {
                $residentId = $this->request->getPost('resident_id');
                $this->residentModel->update($residentId, ['user_id' => $userId]);
            }

            // Commit transaction
            $this->model->db->transComplete();

            return redirect()->to('/users')->with('success', 'Data pengguna berhasil ditambahkan');
        } catch (\Exception $e) {
            // Rollback transaction on error
            $this->model->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data pengguna: ' . $e->getMessage());
        }
    }

    public function edit($id = null)
    {
        // Check if user is admin
        if ($this->session->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $user = $this->model->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'Pengguna tidak ditemukan');
        }

        // Get residents that don't have user accounts yet, plus the current resident if user is a resident
        $available_residents = $this->residentModel->where('user_id IS NULL')->findAll();
        
        // If user is a resident, get their resident data
        $current_resident = null;
        if ($user['role'] === 'resident') {
            $current_resident = $this->residentModel->where('user_id', $id)->first();
            if ($current_resident) {
                $available_residents[] = $current_resident;
            }
        }

        $data = [
            'title' => 'Edit Data Pengguna',
            'user' => $user,
            'available_residents' => $available_residents
        ];
        return view('users/edit', $data);
    }

    public function update($id = null)
    {
        // Check if user is admin
        if ($this->session->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $user = $this->model->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'Pengguna tidak ditemukan');
        }

        $rules = [
            'username' => "required|min_length[3]|is_unique[users.username,id,{$id}]",
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'name' => 'required',
            'role' => 'required|in_list[admin,staff,resident]',
            'status' => 'required|in_list[active,inactive]'
        ];

        // Add password validation if password is provided
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[8]';
            $rules['password_confirmation'] = 'matches[password]';
        }

        // Add resident_id validation if role is resident
        if ($this->request->getPost('role') === 'resident') {
            $rules['resident_id'] = 'required|numeric|is_not_unique[residents.id]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'name' => $this->request->getPost('name'),
            'role' => $this->request->getPost('role'),
            'status' => $this->request->getPost('status')
        ];

        // Update password if provided
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        // Start database transaction
        $this->model->db->transStart();

        try {
            // Update user data
            $this->model->update($id, $data);

            // Handle resident association
            if ($this->request->getPost('role') === 'resident') {
                $residentId = $this->request->getPost('resident_id');
                
                // If user was previously a resident, clear their old resident's user_id
                if ($user['role'] === 'resident') {
                    $oldResident = $this->residentModel->where('user_id', $id)->first();
                    if ($oldResident) {
                        $this->residentModel->update($oldResident['id'], ['user_id' => null]);
                    }
                }

                // Update the new resident's user_id
                $this->residentModel->update($residentId, ['user_id' => $id]);
            } else if ($user['role'] === 'resident') {
                // If changing from resident to another role, clear the resident's user_id
                $oldResident = $this->residentModel->where('user_id', $id)->first();
                if ($oldResident) {
                    $this->residentModel->update($oldResident['id'], ['user_id' => null]);
                }
            }

            // Commit transaction
            $this->model->db->transComplete();

            return redirect()->to('/users')->with('success', 'Data pengguna berhasil diperbarui');
        } catch (\Exception $e) {
            // Rollback transaction on error
            $this->model->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data pengguna: ' . $e->getMessage());
        }
    }

    public function delete($id = null)
    {
        // Check if user is admin
        if ($this->session->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $user = $this->model->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'Pengguna tidak ditemukan');
        }

        // Start database transaction
        $this->model->db->transStart();

        try {
            // If user is a resident, clear their resident's user_id
            if ($user['role'] === 'resident') {
                $resident = $this->residentModel->where('user_id', $id)->first();
                if ($resident) {
                    $this->residentModel->update($resident['id'], ['user_id' => null]);
                }
            }

            // Delete the user
            $this->model->delete($id);

            // Commit transaction
            $this->model->db->transComplete();

            return redirect()->to('/users')->with('success', 'Data pengguna berhasil dihapus');
        } catch (\Exception $e) {
            // Rollback transaction on error
            $this->model->db->transRollback();
            return redirect()->back()->with('error', 'Gagal menghapus data pengguna: ' . $e->getMessage());
        }
    }

    public function getDataTable()
    {
        // Check if user is admin
        if ($this->session->get('role') !== 'admin') {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Anda tidak memiliki akses ke halaman ini']);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Akses langsung tidak diizinkan']);
        }

    
        $builder = $this->model;

        // Get DataTables parameters
        $draw = intval($this->request->getGet('draw'));
        $start = intval($this->request->getGet('start'));
        $length = intval($this->request->getGet('length'));
        $search = $this->request->getGet('search')['value'] ?? '';
        $order = $this->request->getGet('order');
        
        // Get filter parameters
        $role = $this->request->getGet('role');
        $status = $this->request->getGet('status');

        // Apply role filter
        if (!empty($role)) {
            $builder->where('role', $role);
        }

        // Apply status filter
        if (!empty($status)) {
            $builder->where('status', $status);
        }

        // Get total records count
        $totalRecords = $builder->countAllResults(false);

        // Apply search
        if (!empty($search)) {
            $builder->groupStart()
                ->like('username', $search)
                ->orLike('name', $search)
                ->orLike('email', $search)
                ->orLike('role', $search)
                ->orLike('status', $search)
                ->groupEnd();
        }

        // Get filtered records count
        $filteredRecords = $builder->countAllResults(false);

        // Apply ordering
        if (!empty($order)) {
            $columns = ['username', 'name', 'email', 'role', 'status'];
            $columnIndex = $order[0]['column'];
            $columnName = $columns[$columnIndex];
            $columnDirection = $order[0]['dir'];
            $builder->orderBy($columnName, $columnDirection);
        } else {
            $builder->orderBy('created_at', 'DESC');
        }

        // Apply pagination
        $builder->limit($length, $start);

        // Get records
        $records = $builder->get()->getResultArray();

        // Format data for DataTables
        $data = [];
        foreach ($records as $index => $record) {
            $rowNumber = $start + $index + 1;
            $data[] = [
                'no' => $rowNumber,
                'username' => $record['username'],
                'name' => $record['name'],
                'email' => $record['email'],
                'role' => ucfirst($record['role']),
                'status' => $record['status'] === 'active' ? 
                    '<span class="badge bg-success">Aktif</span>' : 
                    '<span class="badge bg-danger">Tidak Aktif</span>',
                'actions' => '<div class="btn-group">
                    <a href="' . base_url('users/show/' . $record['id']) . '" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="' . base_url('users/edit/' . $record['id']) . '" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="' . base_url('users/delete/' . $record['id']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Apakah Anda yakin ingin menghapus pengguna ini?\')">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>'
            ];
        }

        // Prepare response
        $response = [
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ];

        return $this->response->setJSON($response);
    }
} 