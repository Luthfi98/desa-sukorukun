<?php

namespace App\Controllers;

use App\Models\LetterRequestModel;
use App\Models\LetterTypeModel;
use App\Models\ResidentModel;

class SuratPengantar extends BaseController
{
    protected $letterRequestModel;
    protected $letterTypeModel;
    protected $residentModel;
    
    public function __construct()
    {
        $this->letterRequestModel = new LetterRequestModel();
        $this->letterTypeModel = new LetterTypeModel();
        $this->residentModel = new ResidentModel();
    }
    
    public function index()
    {
        // Check if user is logged in as resident
        if (session()->get('role') !== 'resident') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Get resident data for the logged-in user
        $resident = $this->residentModel->where('user_id', session()->get('user_id'))->first();
        
        if (!$resident) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Data penduduk tidak ditemukan. Silakan hubungi administrator.');
        }
        
        // Get all letter requests by the user
        $requests = $this->letterRequestModel->getByResidentId($resident['id']);
        
        $data = [
            'title' => 'Pengajuan Surat Pengantar',
            'requests' => $requests
        ];
        
        return view('surat_pengantar/index', $data);
    }
    
    public function new()
    {
        // Check if user is logged in as resident
        if (session()->get('role') !== 'resident') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Get resident data for the logged-in user
        $resident = $this->residentModel->where('user_id', session()->get('user_id'))->first();
        
        if (!$resident) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Data penduduk tidak ditemukan. Silakan hubungi administrator.');
        }
        
        // Get all available letter types
        $letterTypes = $this->letterTypeModel->findAll();
        
        $data = [
            'title' => 'Ajukan Surat Baru',
            'resident' => $resident,
            'letterTypes' => $letterTypes
        ];
        
        return view('surat_pengantar/create', $data);
    }
    
    public function create()
    {
        // Check if user is logged in as resident
        if (session()->get('role') !== 'resident') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Get resident data for the logged-in user
        $resident = $this->residentModel->where('user_id', session()->get('user_id'))->first();
        
        if (!$resident) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Data penduduk tidak ditemukan. Silakan hubungi administrator.');
        }
        
        $rules = [
            'letter_type_id' => 'required|numeric',
            'purpose' => 'required',
            'description' => 'permit_empty'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $letterTypeId = $this->request->getPost('letter_type_id');
        $purpose = $this->request->getPost('purpose');
        $description = $this->request->getPost('description');
        
        // Create letter request
        $this->letterRequestModel->insert([
            'resident_id' => $resident['id'],
            'letter_type_id' => $letterTypeId,
            'purpose' => $purpose,
            'description' => $description,
            'status' => 'pending'
        ]);
        
        return redirect()->to(base_url('surat-pengantar'))->with('message', 'Pengajuan surat berhasil ditambahkan. Silakan tunggu proses verifikasi.');
    }
    
    public function view($id)
    {
        // Check if user is logged in as resident
        if (session()->get('role') !== 'resident') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Get resident data for the logged-in user
        $resident = $this->residentModel->where('user_id', session()->get('user_id'))->first();
        
        if (!$resident) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Data penduduk tidak ditemukan. Silakan hubungi administrator.');
        }
        
        // Get letter request
        $request = $this->letterRequestModel->find($id);
        
        if (!$request || $request['resident_id'] != $resident['id']) {
            return redirect()->to(base_url('surat-pengantar'))->with('error', 'Pengajuan surat tidak ditemukan');
        }
        
        $letterType = $this->letterTypeModel->find($request['letter_type_id']);
        
        $data = [
            'title' => 'Detail Pengajuan Surat',
            'request' => $request,
            'letterType' => $letterType,
            'resident' => $resident
        ];
        
        return view('surat_pengantar/view', $data);
    }
    
    // Download letter (only available for approved/completed letters)
    public function download($id)
    {
        // Check if user is logged in as resident
        if (session()->get('role') !== 'resident') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Get resident data for the logged-in user
        $resident = $this->residentModel->where('user_id', session()->get('user_id'))->first();
        
        if (!$resident) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Data penduduk tidak ditemukan. Silakan hubungi administrator.');
        }
        
        // Get letter request
        $request = $this->letterRequestModel->find($id);
        
        if (!$request || $request['resident_id'] != $resident['id']) {
            return redirect()->to(base_url('surat-pengantar'))->with('error', 'Pengajuan surat tidak ditemukan');
        }
        
        // Check if letter is approved or completed
        if ($request['status'] !== 'approved' && $request['status'] !== 'completed') {
            return redirect()->to(base_url('surat-pengantar'))->with('error', 'Surat belum disetujui atau diproses');
        }
        
        $letterType = $this->letterTypeModel->find($request['letter_type_id']);
        
        // Generate PDF based on letter type template
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->setPaper('A4', 'portrait');
        
        // Render the letter template with data
        $html = view('letter_templates/' . $letterType['template_file'], [
            'request' => $request,
            'resident' => $resident,
            'letterType' => $letterType
        ]);
        
        $dompdf->loadHtml($html);
        $dompdf->render();
        
        // Generate filename
        $filename = 'surat_' . strtolower(str_replace(' ', '_', $letterType['name'])) . '_' . $resident['nik'] . '.pdf';
        
        // Stream the file
        return $dompdf->stream($filename);
    }
} 