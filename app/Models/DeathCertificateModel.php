<?php

namespace App\Models;

use CodeIgniter\Model;

class DeathCertificateModel extends Model
{
    protected $table            = 'death_certificates';
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
        'nik',
        'gender',
        'dob',
        'pob',
        'religion',
        'address',
        'death_date',
        'location',
        'reason',
        'report_name',
        'report_nik',
        'report_occupation',
        'report_address',
        'report_dob',
        'relation',
        'letter_date',
        'village_head_name',
        'village_head_nip',
        'village_head_position',
        'village_head_signature',
        'status',
        'processed_by',
        'created_by',
        'rejected_reason'
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
        // 'resident_id' => 'required|numeric',
        'name' => 'required|max_length[100]',
        // 'nik' => 'permit_empty',
        'gender' => 'required|in_list[male,female]',
        'pob' => 'required',
        'dob' => 'required|valid_date',
        'religion' => 'required|max_length[10]',
        'address' => 'required',
        'death_date' => 'required|valid_date',
        'location' => 'required',
        'reason' => 'required',
        'report_name' => 'required|max_length[100]',
        'report_dob' => 'required|valid_date',
        'report_nik' => 'required|numeric|exact_length[16]',
        'report_occupation' => 'required|max_length[100]',
        'report_address' => 'required',
        'relation' => 'required|max_length[25]',
        'letter_date' => 'required|valid_date',
        'village_head_name' => 'required|max_length[100]',
        'village_head_nip' => 'required|max_length[20]',
        'village_head_position' => 'required|max_length[100]',
        'status' => 'required|in_list[pending,processing,approved,rejected,completed]'
    ];

    protected $validationMessages   = [
        'letter_type_id' => [
            'required' => 'Jenis surat harus dipilih',
            'numeric'  => 'ID jenis surat tidak valid'
        ],
        'name' => [
            'required'    => 'Nama harus diisi',
            'max_length'  => 'Nama maksimal 100 karakter'
        ],
        'nik' => [
            'numeric'     => 'NIK harus berupa angka',
            'exact_length' => 'NIK harus 16 digit'
        ],
        'gender' => [
            'required'    => 'Jenis kelamin harus dipilih',
            'in_list'     => 'Jenis kelamin tidak valid'
        ],
        'dob' => [
            'required'     => 'Tanggal lahir harus diisi',
            'valid_date'   => 'Format tanggal lahir tidak valid'
        ],
        'religion' => [
            'required'    => 'Agama harus diisi',
            'max_length'  => 'Agama maksimal 10 karakter'
        ],
        'address' => [
            'required' => 'Alamat harus diisi'
        ],
        'death_date' => [
            'required'     => 'Tanggal kematian harus diisi',
            'valid_date'   => 'Format tanggal kematian tidak valid'
        ],
        'location' => [
            'required' => 'Lokasi kematian harus diisi'
        ],
        'reason' => [
            'required' => 'Sebab kematian harus diisi'
        ],
        'report_name' => [
            'required'    => 'Nama pelapor harus diisi',
            'max_length'  => 'Nama pelapor maksimal 100 karakter'
        ],
        'report_nik' => [
            'required'     => 'NIK pelapor harus diisi',
            'numeric'      => 'NIK pelapor harus berupa angka',
            'exact_length' => 'NIK pelapor harus 16 digit'
        ],
        'report_occupation' => [
            'required'    => 'Pekerjaan pelapor harus diisi',
            'max_length'  => 'Pekerjaan pelapor maksimal 100 karakter'
        ],
        'report_address' => [
            'required' => 'Alamat pelapor harus diisi'
        ],
        'relation' => [
            'required'    => 'Hubungan dengan yang meninggal harus diisi',
            'max_length'  => 'Hubungan maksimal 25 karakter'
        ],
        'letter_date' => [
            'required'     => 'Tanggal surat harus diisi',
            'valid_date'   => 'Format tanggal surat tidak valid'
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
        ],
        'status' => [
            'required' => 'Status surat harus ditentukan',
            'in_list'  => 'Status surat tidak valid'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Process death certificate request
     */
    public function processRequest(int $id, int $processedBy, string $status, string $rejectionReason = null)
    {
        $data = [
            'status' => $status,
            'processed_by' => $processedBy,
        ];

        if ($status == 'completed' || $status == 'approved') {
            $data['letter_date'] = date('Y-m-d');
        }
        
        if ($status === 'rejected' && $rejectionReason) {
            $data['rejected_reason'] = $rejectionReason;
        }
        
        return $this->update($id, $data);
    }

    /**
     * Get death certificates by status
     */
    public function getByStatus(string $status)
    {
        return $this->select('
                death_certificates.*,
                letter_types.name as letter_type_name, 
                letter_types.code as letter_type_code,
                residents.name as resident_name,
                residents.nik as resident_nik
            ')
            ->join('letter_types', 'letter_types.id = death_certificates.letter_type_id')
            ->join('residents', 'residents.id = death_certificates.created_by')
            ->where('death_certificates.status', $status)
            ->orderBy('death_certificates.created_at', 'DESC')
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

    
} 