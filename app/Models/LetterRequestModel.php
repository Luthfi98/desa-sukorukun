<?php

namespace App\Models;

use CodeIgniter\Model;

class LetterRequestModel extends Model
{
    protected $table            = 'letter_requests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'resident_id', 'letter_type_id', 'number', 'purpose', 'description', 'status',
        'processed_by', 'processed_at', 'rejection_reason', 'completed_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'resident_id'    => 'required|numeric',
        'letter_type_id' => 'required|numeric',
        'purpose'        => 'required',
        'status'         => 'required|in_list[pending,processing,approved,rejected,completed]',
    ];
    protected $validationMessages   = [
        'resident_id' => [
            'required' => 'Penduduk harus dipilih',
            'numeric' => 'ID penduduk tidak valid'
        ],
        'letter_type_id' => [
            'required' => 'Jenis surat harus dipilih',
            'numeric' => 'ID jenis surat tidak valid'
        ],
        'purpose' => [
            'required' => 'Tujuan pembuatan surat harus diisi'
        ],
        'status' => [
            'required' => 'Status surat harus ditentukan',
            'in_list' => 'Status surat tidak valid'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Count letter requests by status
     */
    public function countByStatus(string $status)
    {
        return $this->where('status', $status)
                    ->countAllResults();
    }

    /**
     * Get letter requests with related data
     */
    public function getWithRelations()
    {
        $requests = $this->findAll();
        $residentModel = new ResidentModel();
        $letterTypeModel = new LetterTypeModel();
        $userModel = new UserModel();
        
        foreach ($requests as &$request) {
            // Get resident information
            $resident = $residentModel->find($request['resident_id']);
            if ($resident) {
                $request['resident_name'] = $resident['name'] ?? 'Unknown';
            } else {
                $request['resident_name'] = 'Unknown';
            }
            
            // Get letter type information
            $letterType = $letterTypeModel->find($request['letter_type_id']);
            if ($letterType) {
                $request['letter_type_name'] = $letterType['name'] ?? 'Unknown';
                $request['letter_type_code'] = $letterType['code'] ?? 'Unknown';
            } else {
                $request['letter_type_name'] = 'Unknown';
                $request['letter_type_code'] = 'Unknown';
            }
            
            // Get processor information if request was processed
            if (!empty($request['processed_by'])) {
                $processor = $userModel->find($request['processed_by']);
                if ($processor) {
                    $request['processor_name'] = $processor['name'] ?? 'Unknown';
                } else {
                    $request['processor_name'] = 'Unknown';
                }
            }
        }
        
        return $requests;
    }

    /**
     * Get letter requests by resident ID
     */
    public function getByResidentId(int $residentId)
    {
        $requests = $this->where('resident_id', $residentId)
                        ->orderBy('created_at', 'DESC')
                        ->findAll();
        
        $letterTypeModel = new LetterTypeModel();
        $userModel = new UserModel();
        
        foreach ($requests as &$request) {
            // Get letter type information
            $letterType = $letterTypeModel->find($request['letter_type_id']);
            if ($letterType) {
                $request['letter_type_name'] = $letterType['name'] ?? 'Unknown';
                $request['letter_type_code'] = $letterType['code'] ?? 'Unknown';
            } else {
                $request['letter_type_name'] = 'Unknown';
                $request['letter_type_code'] = 'Unknown';
            }
            
            // Get processor information if request was processed
            if (!empty($request['processed_by'])) {
                $processor = $userModel->find($request['processed_by']);
                if ($processor) {
                    $request['processor_name'] = $processor['name'] ?? 'Unknown';
                } else {
                    $request['processor_name'] = 'Unknown';
                }
            }
        }
        
        return $requests;
    }

    /**
     * Get letter requests by status
     */
    public function getByStatus(string $status)
    {
        return $this->select('
                letter_requests.*,
                letter_types.name as letter_type_name, 
                letter_types.code as letter_type_code,
                residents.name as resident_name,
                residents.nik as resident_nik
            ')
            ->join('letter_types', 'letter_types.id = letter_requests.letter_type_id')
            ->join('residents', 'residents.id = letter_requests.resident_id')
            ->where('letter_requests.status', $status)
            ->orderBy('letter_requests.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Process a letter request
     */
    public function processRequest(int $id, int $processedBy, string $status, string $rejectionReason = null)
    {
        $data = [
            'status' => $status,
            'processed_by' => $processedBy,
            'processed_at' => date('Y-m-d H:i:s'),
        ];
        
        if ($status === 'rejected' && $rejectionReason) {
            $data['rejection_reason'] = $rejectionReason;
        }
        
        if ($status === 'completed') {
            $data['completed_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->update($id, $data);
    }

    /**
     * Generate reference number for letter
     */
    public function generateReferenceNumber(int $id, string $letterTypeCode)
    {
        $date = date('Y/m/d');
        $refNumber = $letterTypeCode . '/' . str_pad($id, 3, '0', STR_PAD_LEFT) . '/' . $date;
        
        $this->update($id, ['number' => $refNumber]);
        
        return $refNumber;
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics()
    {
        return [
            'pending' => $this->countByStatus('pending'),
            'processing' => $this->countByStatus('processing'),
            'approved' => $this->countByStatus('approved'),
            'rejected' => $this->countByStatus('rejected'),
            'completed' => $this->countByStatus('completed'),
            'total' => $this->countAllResults(),
        ];
    }

    /**
     * Debug method to get table structure
     */
    public function getTableInfo()
    {
        $db = \Config\Database::connect();
        $query = $db->query("DESCRIBE " . $this->table);
        return $query->getResultArray();
    }
} 