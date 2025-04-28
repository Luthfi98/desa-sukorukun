<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsModel extends Model
{
    protected $table = 'news';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'user_id',
        'title',
        'slug',
        'content',
        'image',
        'type',
        'category',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|numeric',
        'title' => 'required|min_length[3]|max_length[255]',
        'slug' => 'required|min_length[3]|max_length[255]|is_unique[news.slug,id,{id}]',
        'content' => 'required',
        'type' => 'required|in_list[news,information]',
        'status' => 'required|in_list[draft,published,active,inactive]'
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID is required',
            'numeric' => 'User ID must be a number'
        ],
        'title' => [
            'required' => 'Title is required',
            'min_length' => 'Title must be at least 3 characters long',
            'max_length' => 'Title cannot exceed 255 characters'
        ],
        'slug' => [
            'required' => 'Slug is required',
            'min_length' => 'Slug must be at least 3 characters long',
            'max_length' => 'Slug cannot exceed 255 characters',
            'is_unique' => 'This slug is already in use'
        ],
        'content' => [
            'required' => 'Content is required'
        ],
        'type' => [
            'required' => 'Type is required',
            'in_list' => 'Type must be either news or information'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Status must be draft, published, active, or inactive'
        ]
    ];

    // Callbacks
    protected $beforeInsert = ['generateSlug'];
    protected $beforeUpdate = ['generateSlug'];

    protected function generateSlug(array $data)
    {
        if (isset($data['data']['title'])) {
            $data['data']['slug'] = url_title($data['data']['title'], '-', true);
        }
        return $data;
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo('App\Models\UserModel', 'user_id', 'id');
    }
} 