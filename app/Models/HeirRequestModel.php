<?php

namespace App\Models;

use CodeIgniter\Model;

class HeirRequestModel extends Model
{
    protected $table            = 'heir_certificates';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'letter_type_id',
        'resident_id',
        'number',
        'name',
        'pob',
        'dob',
        'religion',
        'occupation',
        'address',
        'date_of_death',
        'time_of_death',
        'place_of_death',
        'cause_of_death',
        'burial_location',
        'burial_date',
        'heir_data',
        'processed_by',
        'status',
        'rejected_reason',
        'village_head_name',
        'village_head_nip',
        'village_head_position',
        'village_head_signature',
        'subdistrict_head_name',
        'subdistrict_head_nip',
        'subdistrict_head_position',
        'subdistrict_head_signature'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'letter_type_id' => 'required|numeric',
        'resident_id'    => 'required|numeric',
        'name'           => 'required|max_length[200]',
        'pob'            => 'required|max_length[50]',
        'dob'            => 'required|valid_date',
        'religion'       => 'required|max_length[50]',
        'occupation'     => 'required|max_length[100]',
        'address'        => 'required',
        'date_of_death'  => 'required|valid_date',
        'time_of_death'  => 'required',
        'place_of_death' => 'required',
        'cause_of_death' => 'permit_empty|max_length[100]',
        'burial_location' => 'required',
        'burial_date'    => 'permit_empty|valid_date',
        'heir_data'      => 'permit_empty|valid_json',
        // 'processed_by'   => 'required|numeric',
        'status'         => 'required|in_list[pending,processing,approved,rejected,completed]',
        'village_head_name' => 'required|max_length[100]',
        'village_head_nip' => 'required|max_length[20]',
        'village_head_position' => 'required|max_length[100]',
        'village_head_signature' => 'permit_empty',
        'subdistrict_head_name' => 'required|max_length[100]',
        'subdistrict_head_nip' => 'required|max_length[20]',
        'subdistrict_head_position' => 'required|max_length[100]',
        'subdistrict_head_signature' => 'permit_empty'
    ];

    protected $validationMessages   = [
        'letter_type_id' => [
            'required' => 'Jenis surat harus dipilih',
            'numeric'  => 'ID jenis surat tidak valid'
        ],
        'resident_id' => [
            'required' => 'Penduduk harus dipilih',
            'numeric'  => 'ID penduduk tidak valid'
        ],
        'name' => [
            'required'    => 'Nama harus diisi',
            'max_length'  => 'Nama maksimal 200 karakter'
        ],
        'pob' => [
            'required'    => 'Tempat lahir harus diisi',
            'max_length'  => 'Tempat lahir maksimal 50 karakter'
        ],
        'dob' => [
            'required'     => 'Tanggal lahir harus diisi',
            'valid_date'   => 'Format tanggal lahir tidak valid'
        ],
        'religion' => [
            'required'    => 'Agama harus diisi',
            'max_length'  => 'Agama maksimal 50 karakter'
        ],
        'occupation' => [
            'required'    => 'Pekerjaan harus diisi',
            'max_length'  => 'Pekerjaan maksimal 100 karakter'
        ],
        'address' => [
            'required' => 'Alamat harus diisi'
        ],
        'date_of_death' => [
            'required'     => 'Tanggal kematian harus diisi',
            'valid_date'   => 'Format tanggal kematian tidak valid'
        ],
        'time_of_death' => [
            'required'     => 'Waktu kematian harus diisi',
            'valid_time'   => 'Format waktu kematian tidak valid'
        ],
        'place_of_death' => [
            'required' => 'Tempat kematian harus diisi'
        ],
        'cause_of_death' => [
            'max_length' => 'Sebab kematian maksimal 100 karakter'
        ],
        'burial_location' => [
            'required' => 'Lokasi pemakaman harus diisi'
        ],
        'burial_date' => [
            'valid_date' => 'Format tanggal pemakaman tidak valid'
        ],
        'heir_data' => [
            'valid_json' => 'Format data ahli waris tidak valid'
        ],
        'processed_by' => [
            'required' => 'Pemroses harus diisi',
            'numeric'  => 'ID pemroses tidak valid'
        ],
        'status' => [
            'required' => 'Status harus diisi',
            'in_list'  => 'Status tidak valid'
        ],
        'village_head_name' => [
            'required' => 'Nama kepala desa harus diisi',
            'max_length' => 'Nama kepala desa maksimal 100 karakter'
        ],
        'village_head_nip' => [
            'required' => 'NIP kepala desa harus diisi',
            'max_length' => 'NIP kepala desa maksimal 20 karakter'
        ],
        'village_head_position' => [
            'required' => 'Jabatan kepala desa harus diisi',
            'max_length' => 'Jabatan kepala desa maksimal 100 karakter'
        ],
        'subdistrict_head_name' => [
            'required' => 'Nama camat harus diisi',
            'max_length' => 'Nama camat maksimal 100 karakter'
        ],
        'subdistrict_head_nip' => [
            'required' => 'NIP camat harus diisi',
            'max_length' => 'NIP camat maksimal 20 karakter'
        ],
        'subdistrict_head_position' => [
            'required' => 'Jabatan camat harus diisi',
            'max_length' => 'Jabatan camat maksimal 100 karakter'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get heir certificates with related data
     */
    public function getWithRelations()
    {
        $certificates = $this->findAll();
        $residentModel = new ResidentModel();
        $letterTypeModel = new LetterTypeModel();
        $userModel = new UserModel();
        
        foreach ($certificates as &$certificate) {
            // Get resident information
            $resident = $residentModel->find($certificate['resident_id']);
            if ($resident) {
                $certificate['resident_name'] = $resident['name'] ?? 'Unknown';
            } else {
                $certificate['resident_name'] = 'Unknown';
            }
            
            // Get letter type information
            $letterType = $letterTypeModel->find($certificate['letter_type_id']);
            if ($letterType) {
                $certificate['letter_type_name'] = $letterType['name'] ?? 'Unknown';
            } else {
                $certificate['letter_type_name'] = 'Unknown';
            }
            
            // Get processor information
            if (!empty($certificate['processed_by'])) {
                $processor = $userModel->find($certificate['processed_by']);
                if ($processor) {
                    $certificate['processor_name'] = $processor['name'] ?? 'Unknown';
                } else {
                    $certificate['processor_name'] = 'Unknown';
                }
            }
        }
        
        return $certificates;
    }

    /**
     * Get heir certificates by resident ID
     */
    public function getByResidentId(int $residentId)
    {
        return $this->where('resident_id', $residentId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get heir certificates by letter type ID
     */
    public function getByLetterTypeId(int $letterTypeId)
    {
        return $this->where('letter_type_id', $letterTypeId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Generate certificate number
     */
    public function generateCertificateNumber(int $id, string $letterTypeCode)
    {
        $date = date('Y/m/d');
        $certNumber = $letterTypeCode . '/' . str_pad($id, 3, '0', STR_PAD_LEFT) . '/' . $date;
        
        $this->update($id, ['number' => $certNumber]);
        
        return $certNumber;
    }

    /**
     * Get heir certificates by status
     */
    public function getByStatus(string $status)
    {
        return $this->select('
                heir_certificates.*,
                letter_types.name as letter_type_name, 
                letter_types.code as letter_type_code,
                residents.name as resident_name,
                residents.nik as resident_nik
            ')
            ->join('letter_types', 'letter_types.id = heir_certificates.letter_type_id')
            ->join('residents', 'residents.id = heir_certificates.resident_id')
            ->where('heir_certificates.status', $status)
            ->orderBy('heir_certificates.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Process heir certificate request
     */
    public function processRequest(int $id, int $processedBy, string $status, string $rejectionReason = null)
    {
        $data = [
            'status' => $status,
            'processed_by' => $processedBy
        ];

        if ($status == 'rejected' && $rejectionReason) {
            $data['rejected_reason'] = $rejectionReason;
        }
        
        return $this->update($id, $data);
    }
}
