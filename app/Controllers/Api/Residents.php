<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ResidentModel;

class Residents extends BaseController
{
    protected $residentModel;
    
    public function __construct()
    {
        $this->residentModel = new ResidentModel();
    }
    
    public function checkNik($nik)
    {
        // Validate NIK format
        if (strlen($nik) !== 16 || !is_numeric($nik)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Format NIK tidak valid'
            ]);
        }
        
        // Check if resident exists
        $resident = $this->residentModel->where('nik', $nik)->first();
        
        if (!$resident) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'NIK tidak ditemukan'
            ]);
        }

        $resident['address'] = "Desa Sukorukun RT " . $resident['rt'] . " RW " . $resident['rw']. " Kecamatan Jaken Kabupaten Pati";
        
        return $this->response->setJSON([
            'success' => true,
            'resident' => $resident
        ]);
    }
} 