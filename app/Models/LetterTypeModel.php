<?php

namespace App\Models;

use CodeIgniter\Model;

class LetterTypeModel extends Model
{
    protected $table            = 'letter_types';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name', 'code', 'description', 'status', 'template', 'required_documents'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'name'        => 'required|min_length[3]',
        // 'code'        => 'required|min_length[2]|is_unique[letter_types.code,id,{id}]',
        'description' => 'permit_empty',
        'status'      => 'required|in_list[active,inactive]',
        'template'    => 'permit_empty',
        'required_documents' => 'permit_empty'
    ];
    protected $validationMessages   = [
        'name' => [
            'required' => 'Nama jenis surat harus diisi',
            'min_length' => 'Nama jenis surat minimal 3 karakter'
        ],
        'code' => [
            'required' => 'Kode surat harus diisi',
            'min_length' => 'Kode surat minimal 2 karakter',
            'is_unique' => 'Kode surat sudah digunakan'
        ],
        'status' => [
            'required' => 'Status harus diisi',
            'in_list' => 'Status tidak valid'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get active letter types
     */
    public function getActive()
    {
        return $this->where('status', 'active')->findAll();
    }

    /**
     * Get letter type by code
     */
    public function getByCode(string $code)
    {
        return $this->where('code', $code)
                    ->first();
    }

    /**
     * Get required documents as array
     */
    public function getRequiredDocuments(int $id)
    {
        $letterType = $this->find($id);
        
        if (!$letterType) {
            return [];
        }
        
        $documents = explode(',', $letterType['required_documents']);
        return array_map('trim', $documents);
    }

    /**
     * Get all active letter types for form dropdowns
     */
    public function getDropdownList()
    {
        $letterTypes = $this->findAll();
        $list = [];
        
        foreach ($letterTypes as $type) {
            $list[$type['id']] = $type['name'] . ' (' . $type['code'] . ')';
        }
        
        return $list;
    }

    /**
     * Get letter type with its requirements
     */
    public function getWithRequirements($id)
    {
        $letterType = $this->find($id);
        if ($letterType) {
            $letterType['requirements'] = json_decode($letterType['requirements'], true) ?? [];
        }
        return $letterType;
    }
} 