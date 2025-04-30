<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class SettingController extends BaseController
{
    protected $settingModel;
    
    public function __construct()
    {
        $this->settingModel = new SettingModel();
    }
    
    /**
     * Display a listing of settings
     *
     * @return mixed
     */
    public function index()
    {
        $category = $this->request->getGet('category') ?? 'all';
        
        if ($category !== 'all') {
            $settings = $this->settingModel->getAllByCategory($category);
        } else {
            $settings = $this->settingModel->findAll();
        }
        
        $categories = $this->settingModel->select('category')
                                       ->distinct()
                                       ->findAll();
        
        return view('settings/index', [
            'settings' => $settings,
            'categories' => array_column($categories, 'category'),
            'activeCategory' => $category
        ]);
    }
    
    /**
     * Show the form for creating a new setting
     *
     * @return mixed
     */
    public function create()
    {
        return view('settings/create');
    }
    
    /**
     * Store a newly created setting
     *
     * @return mixed
     */
    public function store()
    {
        $rules = [
            'category' => 'required|max_length[50]',
            'key' => 'required|max_length[100]',
            'label' => 'required|max_length[255]',
            // 'value' => 'required',
            'value_type' => 'required|in_list[text,number,date,boolean,json,file,image]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Check if category and key combination already exists
        $existing = $this->settingModel->getByCategoryAndKey(
            $this->request->getPost('category'),
            $this->request->getPost('key')
        );
        
        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Setting with this category and key already exists');
        }

        $value = $this->request->getPost('value');
        $valueType = $this->request->getPost('value_type');

        // Handle file upload for file and image types
        if (in_array($valueType, ['file', 'image'])) {
            $file = $this->request->getFile('value');
            
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $uploadPath = FCPATH . 'uploads/settings/';
                
                // Create directory if it doesn't exist
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $file->move($uploadPath, $newName);
                $value = 'uploads/settings/' . $newName;
            } else {
                return redirect()->back()->withInput()->with('error', 'File upload failed');
            }
        }
        
        $data = [
            'category' => $this->request->getPost('category'),
            'key' => $this->request->getPost('key'),
            'label' => $this->request->getPost('label'),
            'value' => $value,
            'value_type' => $valueType,
            'description' => $this->request->getPost('description'),
            'order' => $this->request->getPost('order') ?? 0,
            'is_public' => $this->request->getPost('is_public') ? 1 : 0,
            'status' => 'active',
            'created_by' => session()->get('user_id')
        ];
        
        $this->settingModel->insert($data);
        
        return redirect()->to('admin/settings')->with('message', 'Setting created successfully');
    }
    
    /**
     * Show the form for editing the specified setting
     *
     * @param int $id
     * @return mixed
     */
    public function edit($id = null)
    {
        $setting = $this->settingModel->find($id);
        
        if (!$setting) {
            return redirect()->to('admin/settings')->with('error', 'Setting not found');
        }
        
        return view('settings/edit', ['setting' => $setting]);
    }
    
    /**
     * Update the specified setting
     *
     * @param int $id
     * @return mixed
     */
    public function update($id = null)
    {
        $setting = $this->settingModel->find($id);
        
        if (!$setting) {
            return redirect()->to('admin/settings')->with('error', 'Setting not found');
        }
        
        $rules = [
            'category' => 'required|max_length[50]',
            'key' => 'required|max_length[100]',
            'label' => 'required|max_length[255]',
            // 'value' => 'required',
            'value_type' => 'required|in_list[text,number,date,boolean,json,file,image]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Check if category and key combination already exists for other settings
        $existing = $this->settingModel->getByCategoryAndKey(
            $this->request->getPost('category'),
            $this->request->getPost('key')
        );
        
        if ($existing && $existing['id'] != $id) {
            return redirect()->back()->withInput()->with('error', 'Setting with this category and key already exists');
        }

        $value = $this->request->getPost('value');
        $valueType = $this->request->getPost('value_type');

        // Handle file upload for file and image types
        if (in_array($valueType, ['file', 'image'])) {
            $file = $this->request->getFile('value');
            
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $uploadPath = FCPATH . 'uploads/settings/';
                
                // Create directory if it doesn't exist
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                // Delete old file if exists
                if ($setting['value'] && file_exists(FCPATH . $setting['value'])) {
                    unlink(FCPATH . $setting['value']);
                }
                
                $file->move($uploadPath, $newName);
                $value = 'uploads/settings/' . $newName;
            } else {
                // If no new file uploaded, keep the existing value
                $value = $setting['value'];
            }
        }
        
        $data = [
            'category' => $this->request->getPost('category'),
            'key' => $this->request->getPost('key'),
            'label' => $this->request->getPost('label'),
            'value' => $value,
            'value_type' => $valueType,
            'description' => $this->request->getPost('description'),
            'order' => $this->request->getPost('order') ?? 0,
            'is_public' => $this->request->getPost('is_public') ? 1 : 0,
            'status' => $this->request->getPost('status'),
            'updated_by' => session()->get('user_id')
        ];
        
        $this->settingModel->update($id, $data);
        
        return redirect()->to('admin/settings')->with('message', 'Setting updated successfully');
    }
    
    /**
     * Delete the specified setting
     *
     * @param int $id
     * @return mixed
     */
    public function delete($id = null)
    {
        $this->settingModel->delete($id);
        
        return redirect()->to('admin/settings')->with('message', 'Setting deleted successfully');
    }

    /**
     * Display profile settings
     *
     * @return mixed
     */
    public function profile()
    {
        $setting = $this->settingModel->where('category', 'informasi_desa')
                                      ->orderBy('order', 'ASC')
                                      ->findAll();
        
        $logo = $this->settingModel->where('category', 'website')->where('key', 'logo')->first();
        $strukturOrganisasi = $this->settingModel->where('category', 'struktur_organisasi')
        ->orderBy('order', 'ASC')
        ->findAll();

        $geografis = $this->settingModel->where('category', 'geografis')
        ->orderBy('order', 'ASC')
        ->findAll();
        return view('settings/profile', [
            'title' => 'Informasi Desa',
            'setting' => $setting, 
            'logo' => $logo,
            'strukturOrganisasi' => $strukturOrganisasi,
            'geografis' => $geografis
        ]);
    }

    /**
     * Update multiple settings for profile page
     *
     * @return mixed
     */
    public function updateProfile()
    {
        $postData = $this->request->getPost();
        
        // Group post data by setting ID
        $settingsData = [];
        foreach ($postData as $key => $value) {
            if (strpos($key, 'id_') === 0) {
                $id = substr($key, 3);
                $settingsData[$id] = [
                    'id' => $id,
                    'value' => $postData['value_' . $id] ?? null,
                    'value_type' => $postData['value_type_' . $id] ?? null,
                    'old_value' => $postData['old_value_' . $id] ?? null
                ];
            }
        }

        // Process each setting
        foreach ($settingsData as $id => $data) {
            $setting = $this->settingModel->find($id);
            
            if (!$setting) {
                continue;
            }

            $updateData = [];
            
            // Handle file uploads for file and image types
            if (in_array($data['value_type'], ['file', 'image'])) {
                $file = $this->request->getFile('value_' . $id);
                
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $uploadPath = FCPATH . 'uploads/settings/';
                    
                    // Create directory if it doesn't exist
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }
                    
                    // Delete old file if exists
                    if ($data['old_value'] && file_exists(FCPATH . $data['old_value'])) {
                        unlink(FCPATH . $data['old_value']);
                    }
                    
                    $file->move($uploadPath, $newName);
                    $updateData['value'] = 'uploads/settings/' . $newName;
                } else {
                    // If no new file uploaded, keep the existing value
                    $updateData['value'] = $data['old_value'];
                }
            } else {
                $updateData['value'] = $data['value'];
            }
            
            // Add update metadata
            $updateData['updated_by'] = session()->get('user_id');
            $updateData['updated_at'] = date('Y-m-d H:i:s');
            
            // Update the setting
            $this->settingModel->update($id, $updateData);
        }
        
        return redirect()->to('village-profile')->with('message', 'Pengaturan berhasil diperbarui');
    }

    /**
     * Get settings data for DataTable
     *
     * @return mixed
     */
    public function getDatatable()
    {
        $request = $this->request;
        $draw = intval($request->getGet('draw'));
        $start = intval($request->getGet('start'));
        $length = intval($request->getGet('length'));
        $search = $request->getGet('search')['value'] ?? '';
        $category = $request->getGet('category') ?? 'all';
        $order = $request->getGet('order');

        // Create base query
        $db = \Config\Database::connect();
        $builder = $db->table('setting');
        $builder->select('setting.*');

        // Apply category filter
        if ($category !== 'all') {
            $builder->where('category', $category);
        }

        // Get total records count
        $totalRecords = $builder->countAllResults(false);

        // Apply search filter if provided
        if (!empty($search)) {
            $builder->groupStart()
                ->like('category', $search)
                ->orLike('key', $search)
                ->orLike('label', $search)
                ->orLike('value', $search)
                ->groupEnd();
        }

        // Get filtered records count
        $filteredRecords = $builder->countAllResults(false);

        // Apply ordering
        $orderColumn = 'setting.created_at';
        $orderDir = 'DESC';

        if (!empty($order)) {
            $columns = [
                0 => 'setting.category',
                1 => 'setting.key',
                2 => 'setting.label',
                3 => 'setting.value',
                4 => 'setting.value_type',
                5 => 'setting.order',
                6 => 'setting.is_public',
                7 => 'setting.status'
            ];

            if (isset($columns[$order[0]['column']])) {
                $orderColumn = $columns[$order[0]['column']];
                $orderDir = $order[0]['dir'];
            }
        }

        // Apply ordering and pagination
        $builder->orderBy($orderColumn, $orderDir);
        $builder->limit($length, $start);

        // Get records
        $data = $builder->get()->getResultArray();

        // Format data for DataTables
        $formattedData = [];
        foreach ($data as $row) {
            $formattedData[] = [
                'id' => $row['id'],
                'category' => ucwords(str_replace('_', ' ', $row['category'])),
                'key' => $row['key'],
                'label' => $row['label'],
                'value' => $row['value_type'] == 'image' && !empty($row['value']) ? 
                    '<img src="' . base_url($row['value']) . '" alt="' . $row['label'] . '" height="50">' : 
                    ($row['value_type'] == 'file' && !empty($row['value']) ? 
                        '<a href="' . base_url($row['value']) . '" target="_blank">View File</a>' : 
                        (strlen($row['value']) > 50 ? substr($row['value'], 0, 50) . '...' : $row['value'])),
                'value_type' => $row['value_type'],
                'order' => $row['order'],
                'is_public' => $row['is_public'] == 1 ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-danger">No</span>',
                'status' => $row['status'] == 'active' ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>',
                'actions' => '<div class="btn-group" role="group">
                    <a href="' . base_url('admin/settings/edit/' . $row['id']) . '" class="btn btn-sm btn-warning" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="' . base_url('admin/settings/delete/' . $row['id']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this setting?\')" title="Delete">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>'
            ];
        }

        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $formattedData
        ]);
    }
} 