<?php

namespace App\Controllers;

use App\Models\ProgramDesaModel;

class ProgramDesa extends BaseController
{
    protected $programDesaModel;
    
    public function __construct()
    {
        $this->programDesaModel = new ProgramDesaModel();
    }
    
    public function index()
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $data = [
            'title' => 'Program Pemerintah Desa',
            'programs' => $this->programDesaModel->findAll()
        ];
        
        return view('program_desa/index', $data);
    }
    
    public function new()
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $data = [
            'title' => 'Tambah Program Desa Baru'
        ];
        
        return view('program_desa/create', $data);
    }
    
    public function create()
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $rules = [
            'nama_program' => 'required',
            'deskripsi' => 'required',
            'tanggal_mulai' => 'required|valid_date',
            'tanggal_selesai' => 'required|valid_date',
            'status' => 'required|in_list[perencanaan,berlangsung,selesai]',
            'anggaran' => 'required|numeric'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $this->programDesaModel->insert([
            'nama_program' => $this->request->getPost('nama_program'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'status' => $this->request->getPost('status'),
            'anggaran' => $this->request->getPost('anggaran'),
            'created_by' => session()->get('user_id')
        ]);
        
        return redirect()->to(base_url('program-desa'))->with('message', 'Program desa berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $program = $this->programDesaModel->find($id);
        
        if (!$program) {
            return redirect()->to(base_url('program-desa'))->with('error', 'Program tidak ditemukan');
        }
        
        $data = [
            'title' => 'Edit Program Desa',
            'program' => $program
        ];
        
        return view('program_desa/edit', $data);
    }
    
    public function update($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $program = $this->programDesaModel->find($id);
        
        if (!$program) {
            return redirect()->to(base_url('program-desa'))->with('error', 'Program tidak ditemukan');
        }
        
        $rules = [
            'nama_program' => 'required',
            'deskripsi' => 'required',
            'tanggal_mulai' => 'required|valid_date',
            'tanggal_selesai' => 'required|valid_date',
            'status' => 'required|in_list[perencanaan,berlangsung,selesai]',
            'anggaran' => 'required|numeric'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $this->programDesaModel->update($id, [
            'nama_program' => $this->request->getPost('nama_program'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'status' => $this->request->getPost('status'),
            'anggaran' => $this->request->getPost('anggaran'),
            'updated_by' => session()->get('user_id')
        ]);
        
        return redirect()->to(base_url('program-desa'))->with('message', 'Program desa berhasil diperbarui');
    }
    
    public function delete($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $program = $this->programDesaModel->find($id);
        
        if (!$program) {
            return redirect()->to(base_url('program-desa'))->with('error', 'Program tidak ditemukan');
        }
        
        $this->programDesaModel->delete($id);
        
        return redirect()->to(base_url('program-desa'))->with('message', 'Program desa berhasil dihapus');
    }
} 