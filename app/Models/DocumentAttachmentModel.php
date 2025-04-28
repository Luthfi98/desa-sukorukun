<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentAttachmentModel extends Model
{
    protected $table            = 'document_attachments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'letter_request_id', 'letter_type_id','name', 'file_path', 'file_type', 'file_size', 'description'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'letter_request_id' => 'required|integer',
        'letter_type_id'    => 'required|integer',
        'name'              => 'required|min_length[2]|max_length[100]',
        'file_path'         => 'required|max_length[255]',
        'file_type'         => 'required|max_length[50]',
        'file_size'         => 'required|integer',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get attachments by letter request ID
     */
    public function getByLetterRequestId(int $letterRequestId, int $letterTypeId = null)
    {
        $builder = $this->where('letter_request_id', $letterRequestId);
        
        if ($letterTypeId) {
            $builder->where('letter_type_id', $letterTypeId);
        }
        // var_dump($builder->findAll());die;

        return $builder->findAll();
    }

    /**
     * Upload a file and save attachment data
     */
    public function uploadAndSave($file, int $letterRequestId, string $description = null)
    {
        if (!$file->isValid() || $file->hasMoved()) {
            return false;
        }

        // Generate new file name
        $newName = $file->getRandomName();
        
        // Define the upload path
        $uploadPath = 'uploads/documents/';
        
        // Make sure directory exists
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        // Move the file
        $file->move($uploadPath, $newName);
        
        // Save to database
        $this->save([
            'letter_request_id' => $letterRequestId,
            'name'              => $file->getClientName(),
            'file_path'         => $uploadPath . $newName,
            'file_type'         => $file->getClientMimeType(),
            'file_size'         => $file->getSize(),
            'description'       => $description,
        ]);
        
        return $this->getInsertID();
    }

    /**
     * Delete a file from storage and the database
     */
    public function deleteWithFile(int $id)
    {
        $attachment = $this->find($id);
        
        if (!$attachment) {
            return false;
        }
        
        // Delete file if it exists
        if (file_exists($attachment['file_path'])) {
            unlink($attachment['file_path']);
        }
        
        // Delete record from database
        return $this->delete($id);
    }
} 