<?php

namespace App\Models;

use CodeIgniter\Model;

class GeneralRequestModel extends Model
{
    protected $table            = 'certificate_letters';
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
        'nationality',
        'rt',
        'rw',
        'purpose',
        'valid_from',
        'description',
        'village_head_name',
        'village_head_nip',
        'village_head_position',
        'village_head_signature',
        'status',
        'rejection_reason',
        'processed_by'
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
        // 'number'         => 'required|max_length[20]',
        'name'           => 'required|max_length[200]',
        'pob'            => 'required|max_length[100]',
        'dob'            => 'required|valid_date',
        'religion'       => 'required|max_length[50]',
        'nationality'    => 'required|max_length[50]',
        'rt'             => 'required|max_length[3]',
        'rw'             => 'required|max_length[3]',
        'purpose'        => 'required|max_length[200]',
        'village_head_name'     => 'required|max_length[100]',
        'village_head_nip'      => 'required|max_length[20]',
        'village_head_position' => 'required|max_length[100]'
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
        'number' => [
            'required'    => 'Nomor surat harus diisi',
            'max_length'  => 'Nomor surat maksimal 20 karakter'
        ],
        'name' => [
            'required'    => 'Nama harus diisi',
            'max_length'  => 'Nama maksimal 200 karakter'
        ],
        'pob' => [
            'required'    => 'Tempat lahir harus diisi',
            'max_length'  => 'Tempat lahir maksimal 100 karakter'
        ],
        'dob' => [
            'required'     => 'Tanggal lahir harus diisi',
            'valid_date'   => 'Format tanggal lahir tidak valid'
        ],
        'religion' => [
            'required'    => 'Agama harus diisi',
            'max_length'  => 'Agama maksimal 50 karakter'
        ],
        'nationality' => [
            'required'    => 'Kewarganegaraan harus diisi',
            'max_length'  => 'Kewarganegaraan maksimal 50 karakter'
        ],
        'rt' => [
            'required'    => 'RT harus diisi',
            'max_length'  => 'RT maksimal 3 karakter'
        ],
        'rw' => [
            'required'    => 'RW harus diisi',
            'max_length'  => 'RW maksimal 3 karakter'
        ],
        'purpose' => [
            'required'    => 'Tujuan harus diisi',
            'max_length'  => 'Tujuan maksimal 200 karakter'
        ],
        'village_head_name' => [
            'required'    => 'Nama kepala desa harus diisi',
            'max_length'  => 'Nama kepala desa maksimal 100 karakter'
        ],
        'village_head_nip' => [
            'required'    => 'NIP kepala desa harus diisi',
            'max_length'  => 'NIP kepala desa maksimal 20 karakter'
        ],
        'village_head_position' => [
            'required'    => 'Jabatan kepala desa harus diisi',
            'max_length'  => 'Jabatan kepala desa maksimal 100 karakter'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get certificate letters with related data
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
     * Get certificate letters by resident ID
     */
    public function getByResidentId(int $residentId)
    {
        return $this->where('resident_id', $residentId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get certificate letters by letter type ID
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
     * Get certificate letters by status
     */
    public function getByStatus(string $status)
    {
        return $this->select('
                certificate_letters.*,
                letter_types.name as letter_type_name, 
                letter_types.code as letter_type_code,
                residents.name as resident_name,
                residents.nik as resident_nik
            ')
            ->join('letter_types', 'letter_types.id = certificate_letters.letter_type_id')
            ->join('residents', 'residents.id = certificate_letters.resident_id')
            ->where('certificate_letters.status', $status)
            ->orderBy('certificate_letters.created_at', 'DESC')
            ->findAll();
    }

    public function generateReferenceNumber(int $id, string $letterTypeCode)
    {
        $date = date('Y/m/d');
        $refNumber = $letterTypeCode . '/' . str_pad($id, 3, '0', STR_PAD_LEFT) . '/' . $date;
        // var_dump($refNumber, $id);die;
        $this->update($id, ['number' => $refNumber]);
        
        return $refNumber;
    }

    public function processRequest(int $id, int $processedBy, string $status, string $rejectionReason = null)
    {
        $data = [
            'status' => $status,
            'processed_by' => $processedBy
        ];
        
        if ($status === 'rejected' && $rejectionReason) {
            $data['rejection_reason'] = $rejectionReason;
        }
        if ($status == 'completed' || $status == 'approved') {
            $data['valid_from'] = date('Y-m-d');
        }
        
        return $this->update($id, $data);
    }
}
