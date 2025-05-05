<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NewsModel;
use App\Models\UserModel;

class NewsController extends BaseController
{
    protected $newsModel;
    protected $userModel;
    
    public function __construct()
    {
        $this->newsModel = new NewsModel();
        $this->userModel = new UserModel();
    }
    
    /**
     * Display a listing of news
     *
     * @return mixed
     */
    public function index()
    {
        $type = $this->request->getGet('type') ?? 'all';
        $status = $this->request->getGet('status') ?? 'all';
        
        $data = [
            'title' => 'Berita & Informasi',
            'type' => $type,
            'status' => $status,
            'types' => ['news', 'information'],
            'statuses' => ['draft', 'published', 'active', 'inactive']
        ];
        
        return view('news/index', $data);
    }
    
    /**
     * Get news data for DataTable
     *
     * @return mixed
     */
    public function getDataTable()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Akses langsung tidak diizinkan']);
        }

        $builder = $this->newsModel;
        $builder->join('users', 'users.id = news.user_id');

        // Get DataTables parameters
        $draw = intval($this->request->getGet('draw'));
        $start = intval($this->request->getGet('start'));
        $length = intval($this->request->getGet('length'));
        $search = $this->request->getGet('search')['value'] ?? '';
        $order = $this->request->getGet('order');
        
        // Get filter parameters
        $type = $this->request->getGet('type');
        $status = $this->request->getGet('status');

        // Apply type filter
        if (!empty($type) && $type !== 'all') {
            $builder->where('type', $type);
        }

        // Apply status filter
        if (!empty($status) && $status !== 'all') {
            $builder->where('status', $status);
        }

        // Get total records count
        $totalRecords = $builder->countAllResults(false);

        // Apply search
        if (!empty($search)) {
            $builder->groupStart()
                ->like('title', $search)
                ->orLike('type', $search)
                ->orLike('category', $search)
                ->orLike('status', $search)
                ->orLike('users.name', $search)
                ->groupEnd();
        }

        // Get filtered records count
        $filteredRecords = $builder->countAllResults(false);

        // Apply ordering
        $orderColumn = 'created_at';
        $orderDir = 'DESC';
        
        if (!empty($order)) {
            $columns = [
                0 => 'title',
                1 => 'type',
                2 => 'category',
                3 => 'status',
                4 => 'users.name',
                5 => 'created_at'
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
        $records = $builder->get()->getResultArray();

        // Format data for DataTables
        $data = [];
        foreach ($records as $record) {
            switch ($record['status']) {
                case 'draft':
                    $statusColor = 'warning';
                    break;
                    
                case 'published':
                    $statusColor = 'info';
                    break;
                    
                case 'active':
                    $statusColor = 'success';
                    break;
                    
                case 'inactive':
                    $statusColor = 'danger';
                    break;
            }
            $data[] = [
                'title' => $record['title'],
                'type' => ucfirst($record['type']),
                'category' => $record['category'],
                'status' => '<span class="badge bg-'.$statusColor.'">'.ucfirst($record['status']).'</span>',
                'author' => $record['name'],
                'created_at' => date('d M Y H:i', strtotime($record['created_at'])),
                'actions' => '<div class="btn-group">
                    <a href="' . base_url('news/view/' . $record['id']) . '" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="' . base_url('news/edit/' . $record['id']) . '" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="' . base_url('news/delete/' . $record['id']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Apakah Anda yakin ingin menghapus berita ini?\')">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>'
            ];
        }

        // Prepare response
        $response = [
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ];

        return $this->response->setJSON($response);
    }
    
    /**
     * Show the form for creating a new news
     *
     * @return mixed
     */
    public function create()
    {
        return view('news/create', [
            'title' => 'Tambah Berita & Informasi',
            'types' => ['news', 'information'],
            'statuses' => ['draft', 'published', 'active', 'inactive']
        ]);
    }
    
    /**
     * Store a newly created news
     *
     * @return mixed
     */
    public function store()
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'content' => 'required',
            'type' => 'required|in_list[news,information]',
            'category' => 'required|max_length[100]',
            'status' => 'required|in_list[draft,published,active,inactive]',
            'image' => 'uploaded[image]|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $image = $this->request->getFile('image');
        $imageName = null;
        
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/news', $imageName);
        }
        
        $data = [
            'user_id' => session()->get('user_id'),
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'type' => $this->request->getPost('type'),
            'category' => $this->request->getPost('category'),
            'status' => $this->request->getPost('status'),
            'image' => $imageName ? 'uploads/news/' . $imageName : null
        ];
        
        
        $this->newsModel->insert($data);
        
        return redirect()->to('news')->with('message', 'News created successfully');
    }
    
    /**
     * Show the form for editing the specified news
     *
     * @param int $id
     * @return mixed
     */
    public function edit($id = null)
    {
        $news = $this->newsModel->find($id);
        
        if (!$news) {
            return redirect()->to('news')->with('error', 'News not found');
        }
        
        return view('news/edit', [
            'news' => $news,
            'types' => ['news', 'information'],
            'statuses' => ['draft', 'published', 'active', 'inactive']
        ]);
    }
    
    /**
     * Update the specified news
     *
     * @param int $id
     * @return mixed
     */
    public function update($id = null)
    {
        $news = $this->newsModel->find($id);
        
        if (!$news) {
            return redirect()->to('news')->with('error', 'News not found');
        }
        
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'content' => 'required',
            'type' => 'required|in_list[news,information]',
            'category' => 'required|max_length[100]',
            'status' => 'required|in_list[draft,published,active,inactive]',
            'image' => 'max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $image = $this->request->getFile('image');
        $imageName = $news['image'];
        
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/news', $imageName);
            
            // Delete old image if exists
            if ($news['image'] && file_exists(FCPATH . $news['image'])) {
                unlink(FCPATH . $news['image']);
            }
            
            $imageName = 'uploads/news/' . $imageName;
        }
        
        $data = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'type' => $this->request->getPost('type'),
            'category' => $this->request->getPost('category'),
            'status' => $this->request->getPost('status'),
            'image' => $imageName
        ];
        
        $this->newsModel->update($id, $data);
        
        return redirect()->to('news')->with('message', 'News updated successfully');
    }
    
    /**
     * Delete the specified news
     *
     * @param int $id
     * @return mixed
     */
    public function delete($id = null)
    {
        $news = $this->newsModel->find($id);
        
        if (!$news) {
            return redirect()->to('news')->with('error', 'News not found');
        }
        
        // Delete image if exists
        if ($news['image'] && file_exists(FCPATH . $news['image'])) {
            unlink(FCPATH . $news['image']);
        }
        
        $this->newsModel->delete($id);
        
        return redirect()->to('news')->with('message', 'News deleted successfully');
    }
    
    /**
     * View the specified news
     *
     * @param int $id
     * @return mixed
     */
    public function view($id = null)
    {
        $news = $this->newsModel->find($id);
        
        if (!$news) {
            return redirect()->to('news')->with('error', 'News not found');
        }
        
        return view('news/show', ['news' => $news]);
    }
} 