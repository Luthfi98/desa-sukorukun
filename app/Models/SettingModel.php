<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table            = 'setting';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'category', 'key', 'label', 'value', 'value_type', 
        'description', 'order', 'is_public', 'status',
        'created_by', 'updated_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'category'    => 'required|max_length[50]',
        'key'         => 'required|max_length[100]',
        'label'       => 'required|max_length[255]',
        'value'       => 'required',
        'value_type'  => 'required|in_list[text,number,date,boolean,json,file,image]',
        'is_public'   => 'required|in_list[0,1]',
        'status'      => 'required|in_list[active,inactive]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Get setting by category and key
     *
     * @param string $category
     * @param string $key
     * @return array|null
     */
    public function getByCategoryAndKey(string $category, string $key = null)
    {
        if ($key) {
            return $this->where('category', $category)
                        ->where('key', $key)
                        ->where('status', 'active')
                        ->first();
        } else {
            return $this->where('category', $category)
                        ->where('status', 'active')
                        ->orderBy('order', 'ASC')
                        ->findAll();
        }
    }

    /**
     * Get all settings by category
     *
     * @param string $category
     * @return array
     */
    public function getAllByCategory(string $category)
    {
        return $this->where('category', $category)
                    ->where('status', 'active')
                    ->orderBy('order', 'ASC')
                    ->findAll();
    }

    /**
     * Get all public settings
     *
     * @return array
     */
    public function getAllPublic()
    {
        return $this->where('is_public', 1)
                    ->where('status', 'active')
                    ->findAll();
    }

    /**
     * Set setting value
     *
     * @param string $category
     * @param string $key
     * @param mixed $value
     * @param int|null $userId
     * @return bool
     */
    public function setSetting(string $category, string $key, $value, ?int $userId = null)
    {
        $setting = $this->getByCategoryAndKey($category, $key);
        
        if ($setting) {
            // Update existing setting
            $data = [
                'value' => $value,
                'updated_by' => $userId
            ];
            
            return $this->update($setting['id'], $data);
        }
        
        return false;
    }
} 