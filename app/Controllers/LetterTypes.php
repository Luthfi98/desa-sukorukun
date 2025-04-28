<?php

namespace App\Controllers;

use App\Models\LetterTypeModel;

class LetterTypes extends BaseController
{
    protected $letterTypeModel;
    
    public function __construct()
    {
        $this->letterTypeModel = new LetterTypeModel();
    }
    
    public function index()
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Check if this is an AJAX request from DataTables
        if ($this->request->isAJAX()) {
            $letterTypes = $this->letterTypeModel->findAll();
            
            $data = [];
            foreach ($letterTypes as $type) {
                $data[] = [
                    'DT_RowId' => $type['id'],
                    'code' => $type['code'],
                    'name' => $type['name'],
                    'description' => $type['description'] ?? '-',
                    'status' => $type['status'] === 'active' ? 
                        '<span class="badge bg-success">Aktif</span>' : 
                        '<span class="badge bg-danger">Tidak Aktif</span>',
                    'actions' => '
                        <div class="btn-group">
                            <a href="' . base_url('letter-types/edit/'.$type['id']) . '" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="' . base_url('letter-types/delete/'.$type['id']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Apakah Anda yakin ingin menghapus jenis surat ini?\')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    '
                ];
            }
            
            return $this->response->setJSON([
                'data' => $data
            ]);
        }
        
        $data = [
            'title' => 'Jenis Surat'
        ];
        
        return view('letter_types/index', $data);
    }
    
    public function new()
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $data = [
            'title' => 'Tambah Jenis Surat',
            'validation' => \Config\Services::validation()
        ];
        
        return view('letter_types/create', $data);
    }
    
    public function create()
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Validate input
        if (!$this->validate([
            'name' => 'required|min_length[3]',
            'code' => 'required|min_length[2]|is_unique[letter_types.code]',
            'description' => 'permit_empty',
            'status' => 'required|in_list[active,inactive]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Get input data
        $data = [
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code'),
            'description' => $this->request->getPost('description'),
            'status' => $this->request->getPost('status'),
            'template' => $this->request->getPost('template'),
            'required_documents' => $this->request->getPost('required_documents')
        ];
        
        // Save to database
        $this->letterTypeModel->insert($data);
        
        return redirect()->to(base_url('letter-types'))->with('message', 'Jenis surat berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $letterType = $this->letterTypeModel->find($id);
        
        if (!$letterType) {
            return redirect()->to(base_url('letter-types'))->with('error', 'Jenis surat tidak ditemukan');
        }
        
        $data = [
            'title' => 'Edit Jenis Surat',
            'letterType' => $letterType,
            'validation' => \Config\Services::validation()
        ];
        
        return view('letter_types/edit', $data);
    }
    
    public function update($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Check if letter type exists
        $letterType = $this->letterTypeModel->find($id);
        if (!$letterType) {
            return redirect()->to(base_url('letter-types'))->with('error', 'Jenis surat tidak ditemukan');
        }
        // Validate input
        if (!$this->validate([
            'name' => 'required|min_length[3]',
            'code' => "required|min_length[2]|is_unique[letter_types.code,id,{$id}]",
            'description' => 'permit_empty',
            'status' => 'required|in_list[active,inactive]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Get input data
        $data = [
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code'),
            'description' => $this->request->getPost('description'),
            'status' => $this->request->getPost('status'),
            'template' => $this->request->getPost('template'),
            'required_documents' => $this->request->getPost('required_documents')
        ];
        
        
        // Update database
        $this->letterTypeModel->update($id, $data);
        
        return redirect()->to(base_url('letter-types'))->with('message', 'Jenis surat berhasil diperbarui');
    }
    
    public function delete($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Check if letter type exists
        $letterType = $this->letterTypeModel->find($id);
        if (!$letterType) {
            return redirect()->to(base_url('letter-types'))->with('error', 'Jenis surat tidak ditemukan');
        }
        
        // Delete from database
        $this->letterTypeModel->delete($id);
        
        return redirect()->to(base_url('letter-types'))->with('message', 'Jenis surat berhasil dihapus');
    }
} 