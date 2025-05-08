<?php

namespace App\Models;

use CodeIgniter\Model;

class RelocationRequestModel extends Model
{
    protected $table            = 'relocation_letters';
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
        'gender',
        'pob',
        'dob',
        'nationality',
        'occupation',
        'education',
        'origin_address',
        'destination_detail',
        'reason',
        'move_followers',
        'move_date',
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
        'resident_id' => 'required|numeric',
        'name' => 'required|max_length[100]',
        'gender' => 'required|in_list[male,female]',
        'pob' => 'required|max_length[100]',
        'move_date' => 'required|valid_date',
        'dob' => 'required|valid_date',
        'nationality' => 'required|max_length[100]',
        'occupation' => 'required|max_length[100]',
        'education' => 'required|max_length[100]',
        'origin_address' => 'required',
        'destination_detail' => 'required',
        'reason' => 'required|max_length[255]',
        'move_followers' => 'required',
        // 'processed_by' => 'numeric',
        'status' => 'required|in_list[pending,processed,approved,rejected,completed]',
        'village_head_name' => 'required|max_length[100]',
        'village_head_nip' => 'required|max_length[20]',
        'village_head_position' => 'required|max_length[100]',
        'subdistrict_head_name' => 'required|max_length[100]',
        'subdistrict_head_nip' => 'required|max_length[20]',
        'subdistrict_head_position' => 'required|max_length[100]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get relocation letters with related data
     */
    public function getWithRelations()
    {
        $letters = $this->findAll();
        $residentModel = new ResidentModel();
        $letterTypeModel = new LetterTypeModel();
        $userModel = new UserModel();
        
        foreach ($letters as &$letter) {
            // Get resident information
            $resident = $residentModel->find($letter['resident_id']);
            if ($resident) {
                $letter['resident_name'] = $resident['name'] ?? 'Unknown';
            } else {
                $letter['resident_name'] = 'Unknown';
            }
            
            // Get letter type information
            $letterType = $letterTypeModel->find($letter['letter_type_id']);
            if ($letterType) {
                $letter['letter_type_name'] = $letterType['name'] ?? 'Unknown';
            } else {
                $letter['letter_type_name'] = 'Unknown';
            }
            
            // Get processor information
            if (!empty($letter['processed_by'])) {
                $processor = $userModel->find($letter['processed_by']);
                if ($processor) {
                    $letter['processor_name'] = $processor['name'] ?? 'Unknown';
                } else {
                    $letter['processor_name'] = 'Unknown';
                }
            }
        }
        
        return $letters;
    }

    /**
     * Get relocation letters by resident ID
     */
    public function getByResidentId(int $residentId)
    {
        return $this->where('resident_id', $residentId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get relocation letters by letter type ID
     */
    public function getByLetterTypeId(int $letterTypeId)
    {
        return $this->where('letter_type_id', $letterTypeId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Generate letter number
     */
    public function generateLetterNumber(int $id, string $letterTypeCode)
    {
        $date = date('Y/m/d');
        $letterNumber = $letterTypeCode . '/' . str_pad($id, 3, '0', STR_PAD_LEFT) . '/' . $date;
        $this->update($id, ['number' => $letterNumber]);
        
        return $letterNumber;
    }

    /**
     * Get relocation letters by status
     */
    public function getByStatus(string $status)
    {
        return $this->select('
                relocation_letters.*,
                letter_types.name as letter_type_name, 
                letter_types.code as letter_type_code,
                residents.name as resident_name,
                residents.nik as resident_nik
            ')
            ->join('letter_types', 'letter_types.id = relocation_letters.letter_type_id')
            ->join('residents', 'residents.id = relocation_letters.resident_id')
            ->where('relocation_letters.status', $status)
            ->orderBy('relocation_letters.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Process relocation letter request
     */
    public function processRequest(int $id, int $processedBy, string $status, string $rejectionReason = null)
    {
        $data = [
            'status' => $status,
            'processed_by' => $processedBy
        ];


        if($status == 'rejected' && $rejectionReason) {
            $data['rejected_reason'] = $rejectionReason;
        }
        
        return $this->update($id, $data);
    }
} 