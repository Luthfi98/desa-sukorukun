<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LetterRequestModel;
use App\Models\UserModel;

class Archive extends BaseController
{
    protected $LetterRequestModel;
    protected $userModel;

    public function __construct()
    {
        $this->LetterRequestModel = new LetterRequestModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Arsip Pengajuan Surat',
            'user' => session()->get('user')
        ];
        return view('archive/index', $data);
    }

    public function getDataTable()
    {
        $user = session()->get('user');
        $isAdmin = in_array($user['role'], ['admin', 'staff']);
        
        $draw = $this->request->getPost('draw');
        $start = $this->request->getPost('start');
        $length = $this->request->getPost('length');
        $search = $this->request->getPost('search')['value'];
        
        // Get date from 1 month ago
        $oneMonthAgo = date('Y-m-d', strtotime('-1 month'));
        
        $builder = $this->LetterRequestModel->select('pengajuan_surat.*, users.nama as nama_pemohon, jenis_surat.nama as jenis_surat')
            ->join('users', 'users.id = pengajuan_surat.user_id')
            ->join('jenis_surat', 'jenis_surat.id = pengajuan_surat.jenis_surat_id')
            ->where('pengajuan_surat.created_at <=', $oneMonthAgo)
            ->whereIn('pengajuan_surat.status', ['selesai', 'ditolak']);
            
        if (!$isAdmin) {
            $builder->where('pengajuan_surat.user_id', $user['id']);
        }
        
        if ($search) {
            $builder->groupStart()
                ->like('users.nama', $search)
                ->orLike('jenis_surat.nama', $search)
                ->orLike('pengajuan_surat.nomor_surat', $search)
                ->groupEnd();
        }
        
        $total = $builder->countAllResults(false);
        
        $builder->limit($length, $start);
        $query = $builder->get();
        $data = [];
        
        foreach ($query->getResult() as $row) {
            $data[] = [
                'id' => $row->id,
                'nomor_surat' => $row->nomor_surat,
                'nama_pemohon' => $row->nama_pemohon,
                'jenis_surat' => $row->jenis_surat,
                'tanggal_pengajuan' => date('d/m/Y', strtotime($row->created_at)),
                'status' => $row->status,
                'actions' => '<a href="' . base_url('archive/view/' . $row->id) . '" class="btn btn-info btn-sm">Detail</a>'
            ];
        }
        
        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ]);
    }

    public function view($id)
    {
        $pengajuan = $this->LetterRequestModel->select('pengajuan_surat.*, users.nama as nama_pemohon, jenis_surat.nama as jenis_surat')
            ->join('users', 'users.id = pengajuan_surat.user_id')
            ->join('jenis_surat', 'jenis_surat.id = pengajuan_surat.jenis_surat_id')
            ->find($id);

        if (!$pengajuan) {
            return redirect()->to('/archive')->with('error', 'Data tidak ditemukan');
        }

        $user = session()->get('user');
        if (!in_array($user['role'], ['admin', 'staff']) && $pengajuan['user_id'] != $user['id']) {
            return redirect()->to('/archive')->with('error', 'Anda tidak memiliki akses ke data ini');
        }

        $data = [
            'title' => 'Detail Arsip Pengajuan Surat',
            'pengajuan' => $pengajuan,
            'user' => $user
        ];

        return view('archive/view', $data);
    }
} 