<?php

namespace App\Controllers;

use App\Models\APBDModel;

class InformasiAPBD extends BaseController
{
    protected $apbdModel;
    
    public function __construct()
    {
        $this->apbdModel = new APBDModel();
    }
    
    public function index()
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $data = [
            'title' => 'Informasi APBD',
            'apbd_data' => $this->apbdModel->findAll()
        ];
        
        return view('informasi_apbd/index', $data);
    }
    
    public function new()
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $data = [
            'title' => 'Tambah Data APBD'
        ];
        
        return view('informasi_apbd/create', $data);
    }
    
    public function create()
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $rules = [
            'tahun' => 'required|numeric',
            'jenis' => 'required|in_list[pendapatan,belanja,pembiayaan]',
            'kategori' => 'required',
            'uraian' => 'required',
            'jumlah' => 'required|numeric',
            'status' => 'required|in_list[rencana,realisasi]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $this->apbdModel->insert([
            'tahun' => $this->request->getPost('tahun'),
            'jenis' => $this->request->getPost('jenis'),
            'kategori' => $this->request->getPost('kategori'),
            'uraian' => $this->request->getPost('uraian'),
            'jumlah' => $this->request->getPost('jumlah'),
            'status' => $this->request->getPost('status'),
            'keterangan' => $this->request->getPost('keterangan'),
            'created_by' => session()->get('user_id')
        ]);
        
        return redirect()->to(base_url('informasi-apbd'))->with('message', 'Data APBD berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $apbd = $this->apbdModel->find($id);
        
        if (!$apbd) {
            return redirect()->to(base_url('informasi-apbd'))->with('error', 'Data APBD tidak ditemukan');
        }
        
        $data = [
            'title' => 'Edit Data APBD',
            'apbd' => $apbd
        ];
        
        return view('informasi_apbd/edit', $data);
    }
    
    public function update($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $apbd = $this->apbdModel->find($id);
        
        if (!$apbd) {
            return redirect()->to(base_url('informasi-apbd'))->with('error', 'Data APBD tidak ditemukan');
        }
        
        $rules = [
            'tahun' => 'required|numeric',
            'jenis' => 'required|in_list[pendapatan,belanja,pembiayaan]',
            'kategori' => 'required',
            'uraian' => 'required',
            'jumlah' => 'required|numeric',
            'status' => 'required|in_list[rencana,realisasi]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $this->apbdModel->update($id, [
            'tahun' => $this->request->getPost('tahun'),
            'jenis' => $this->request->getPost('jenis'),
            'kategori' => $this->request->getPost('kategori'),
            'uraian' => $this->request->getPost('uraian'),
            'jumlah' => $this->request->getPost('jumlah'),
            'status' => $this->request->getPost('status'),
            'keterangan' => $this->request->getPost('keterangan'),
            'updated_by' => session()->get('user_id')
        ]);
        
        return redirect()->to(base_url('informasi-apbd'))->with('message', 'Data APBD berhasil diperbarui');
    }
    
    public function delete($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $apbd = $this->apbdModel->find($id);
        
        if (!$apbd) {
            return redirect()->to(base_url('informasi-apbd'))->with('error', 'Data APBD tidak ditemukan');
        }
        
        $this->apbdModel->delete($id);
        
        return redirect()->to(base_url('informasi-apbd'))->with('message', 'Data APBD berhasil dihapus');
    }
    
    // Public page for APBD information
    public function public()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        
        $data = [
            'title' => 'Informasi APBD Desa',
            'tahun' => $tahun,
            'pendapatan' => $this->apbdModel->where('tahun', $tahun)->where('jenis', 'pendapatan')->findAll(),
            'belanja' => $this->apbdModel->where('tahun', $tahun)->where('jenis', 'belanja')->findAll(),
            'pembiayaan' => $this->apbdModel->where('tahun', $tahun)->where('jenis', 'pembiayaan')->findAll(),
            'tahun_list' => $this->apbdModel->select('tahun')->distinct()->orderBy('tahun', 'DESC')->findAll()
        ];
        
        return view('informasi_apbd/public', $data);
    }
} 