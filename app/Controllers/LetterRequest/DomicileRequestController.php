<?php

namespace App\Controllers\LetterRequest;

use App\Controllers\BaseController;
use App\Models\DomicileRequestModel;
use App\Models\LetterTypeModel;
use App\Models\ResidentModel;
use App\Models\NotificationModel;
use App\Models\SettingModel;
use App\Models\DocumentAttachmentModel;
use App\Models\UserModel;

class DomicileRequestController extends BaseController
{
    protected $DomicileRequestModel;
    protected $letterTypeModel;
    protected $residentModel;
    protected $notificationModel;
    protected $settingModel;
    protected $attachmentModel;
    protected $userModel;
    
    public function __construct()
    {
        $this->DomicileRequestModel = new DomicileRequestModel();
        $this->letterTypeModel = new LetterTypeModel();
        $this->residentModel = new ResidentModel();
        $this->notificationModel = new NotificationModel();
        $this->settingModel = new SettingModel();
        $this->attachmentModel = new DocumentAttachmentModel();
        $this->userModel = new UserModel();
    }
    
    public function index()
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $status = $this->request->getGet('status') ?? 'pending';
        
        $data = [
            'title' => 'Pengajuan SK Domisili',
            'status' => $status,
            'requests' => $this->DomicileRequestModel->getByStatus($status),
            'letterTypes' => $this->letterTypeModel->where('template', 'sk_domisili')->getActive()
        ];
        
        return view('letter_requests/domicile/index', $data);
    }

    public function myRequest()
    {
        if (session()->get('role') !== 'resident') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $resident = $this->residentModel->where('user_id', session()->get('user_id'))->first();
        
        if (!$resident) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Data penduduk tidak ditemukan. Silakan hubungi administrator.');
        }
        $status = $this->request->getGet('status') ?? 'pending';
        
        $data = [
            'title' => 'Pengajuan SK Domisili',
            'status' => $status,
            'requests' => $this->DomicileRequestModel->where('resident_id', $resident['id'])->orderBy('created_at', 'desc')->getByStatus($status),
            'letterTypes' => $this->letterTypeModel->where('template', 'sk_domisili')->getActive()
        ];
        
        return view('letter_requests/domicile/my_request', $data);
    }

    public function getDataTable()
    {
        // Check if user is admin or staff
        
        $status = $this->request->getGet('status') ?? 'pending';
        $letterTypeId = $this->request->getGet('letter_type');
        $dateStart = $this->request->getGet('date_start');
        $dateEnd = $this->request->getGet('date_end');
        $draw = intval($this->request->getGet('draw'));
        $start = intval($this->request->getGet('start'));
        $length = intval($this->request->getGet('length'));
        $search = $this->request->getGet('search')['value'] ?? '';
        $order = $this->request->getGet('order');
        
        // Create base query with joins
        $db = \Config\Database::connect();
        $builder = $db->table('domicile_certificates');
        $builder->select('domicile_certificates.*, letter_types.name as letter_type_name, letter_types.code as letter_type_code, residents.name as resident_name, residents.nik as resident_nik');
        $builder->join('letter_types', 'letter_types.id = domicile_certificates.letter_type_id');
        $builder->join('residents', 'residents.id = domicile_certificates.resident_id');
        $builder->where('domicile_certificates.status', $status);
        $builder->where('domicile_certificates.deleted_at', null);
        $url = 'domicile-request';
        if (session()->get('role') === 'resident') {
            $builder->where('residents.user_id', session()->get('user_id'));
            $url = 'domicile-request/my-request';
        }
        
        // Apply letter type filter
        if (!empty($letterTypeId)) {
            $builder->where('domicile_certificates.letter_type_id', $letterTypeId);
        }
        
        // Apply date range filter
        if (!empty($dateStart)) {
            $builder->where('DATE(domicile_certificates.created_at) >=', $dateStart);
        }
        if (!empty($dateEnd)) {
            $builder->where('DATE(domicile_certificates.created_at) <=', $dateEnd);
        }
        
        // Get total records count
        $totalRecords = $builder->countAllResults(false);
        
        // Apply search filter if provided
        if (!empty($search)) {
            $builder->groupStart()
                ->like('residents.name', $search)
                ->orLike('residents.nik', $search)
                ->orLike('letter_types.name', $search)
                ->orLike('domicile_certificates.number', $search)
                ->orLike('domicile_certificates.purpose', $search)
                ->groupEnd();
        }
        
        // Get filtered records count
        $filteredRecords = $builder->countAllResults(false);
        
        // Apply ordering
        $orderColumn = 'domicile_certificates.created_at';
        $orderDir = 'DESC';
        
        if (!empty($order)) {
            $columns = [
                0 => 'domicile_certificates.created_at',
                1 => 'residents.name',
                2 => 'residents.nik',
                3 => 'letter_types.name',
                4 => 'domicile_certificates.number',
                5 => 'domicile_certificates.purpose'
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
            $actions = '<div class="btn-group">';
            $actions .= '<a href="' . base_url($url.'/view/' . $row['id']) . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';

            if ($status === 'pending') {
                $actions .= '<a href="' . base_url($url.'/edit/' . $row['id']) . '" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>';
                $actions .= '<a href="' . base_url($url.'/delete/' . $row['id']) . '" class="btn btn-sm btn-danger"
                                onclick="return confirm(\'Are you sure you want to delete this request? This action cannot be undone.\')"><i class="fas fa-trash"></i></a>';
            }
            
            if (($status === 'pending' || $status === 'processing') && session()->get('role') !== 'resident') {
                $actions .= '<a href="' . base_url($url.'/process/' . $row['id']) . '" class="btn btn-sm btn-warning"><i class="fas fa-cog"></i></a>';
            }
            
            if ($status === 'approved' || $status === 'completed') {
                $actions .= '<a href="' . base_url($url.'/download/' . $row['id']) . '" class="btn btn-sm btn-success" target="_blank"><i class="fas fa-download"></i></a>';
            }
            
            $actions .= '</div>';
            
            // Format purpose text (truncate if too long)
            $purpose = $row['purpose'];
            if (strlen($purpose) > 30) {
                $purpose = substr($purpose, 0, 30) . '...';
            }
            
            $formattedData[] = [
                date('d-m-Y', strtotime($row['created_at'])),
                $row['resident_name'],
                $row['resident_nik'],
                $row['letter_type_name'],
                $row['number'] ?? '<span class="text-muted">-</span>',
                $purpose,
                $actions
            ];
        }
        
        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $formattedData
        ]);
    }

   

    
    public function show($id)
    {
        
        // Get request data
        $request = $this->DomicileRequestModel->select('domicile_certificates.*,  residents.nik, residents.gender, residents.occupation')->join('residents', 'domicile_certificates.resident_id = residents.id');
        $url = 'domicile-request';
        if (session()->get('role') === 'resident') {
            $request = $request->where('residents.user_id', session()->get('user_id'));
            $url .= '/my-request';
        }
        $request = $request->find($id);
        
        if (!$request) {
            return redirect()->to(base_url('domicile-request'))->with('error', 'Surat tidak ditemukan');
        }
        
        // Get letter type name
        $processedBy = $this->userModel->find($request['processed_by']);
        // var_dump($processedBy);die;
        $request['processor_name'] = $processedBy['name'];

        $letterType = $this->letterTypeModel->find($request['letter_type_id']);
        $request['letter_type_name'] = $letterType['name'];
        $attachments = $this->attachmentModel->getByLetterRequestId($id, $request['letter_type_id']);
        $data = [
            'title' => 'Detail SK Domisili',
            'letterType' => $letterType,
            'request' => $request,
            'attachments' => $attachments,
            'url' => $url
        ];
        
        return view('letter_requests/domicile/show', $data);
    }

    public function process($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $request = $this->DomicileRequestModel->find($id);
        
        if (!$request) {
            return redirect()->to(base_url('domicile-request'))->with('error', 'Pengajuan surat tidak ditemukan');
        }
        
        $letterType = $this->letterTypeModel->find($request['letter_type_id']);
        $resident = $this->residentModel->find($request['resident_id']);
        
        // Get document attachments
        $attachments = $this->attachmentModel->getByLetterRequestId($id, $letterType['id']);
        
        $data = [
            'title' => 'Proses Pengajuan Surat',
            'request' => $request,
            'letterType' => $letterType,
            'resident' => $resident,
            'attachments' => $attachments
        ];
        
        return view('letter_requests/domicile/process', $data);
    }

    public function updateStatus($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $request = $this->DomicileRequestModel->find($id);
        
        if (!$request) {
            return redirect()->to(base_url('domicile-request'))->with('error', 'Pengajuan surat tidak ditemukan');
        }
        
        $status = $this->request->getPost('status');
        $rejectionReason = $this->request->getPost('rejection_reason');
        
        // Process the request
        $this->DomicileRequestModel->processRequest($id, session()->get('user_id'), $status, $rejectionReason);
        
        // Generate reference number if approved
        if (($status === 'approved' || $status === 'completed') && empty($request['number'])) {
            $letterType = $this->letterTypeModel->find($request['letter_type_id']);
            $this->DomicileRequestModel->generateCertificateNumber($id, $letterType['code']);
        }
        
        // Send notification to resident
        $resident = $this->residentModel->where('user_id', session()->get('user_id'))->first();
        if ($resident && !empty($resident['user_id'])) {
            $letterType = $this->letterTypeModel->find($request['letter_type_id']);
            $notifTitle = 'Pengajuan Surat ' . $letterType['name'];
            
            if ($status === 'approved') {
                $notifMessage = 'Pengajuan surat ' . $letterType['name'] . ' Anda telah disetujui dan sedang dalam proses pembuatan.';
            } elseif ($status === 'rejected') {
                $notifMessage = 'Pengajuan surat ' . $letterType['name'] . ' Anda ditolak. Alasan: ' . $rejectionReason;
            } elseif ($status === 'completed') {
                $notifMessage = 'Surat ' . $letterType['name'] . ' Anda telah selesai diproses dan siap untuk diambil.';
            } else {
                $notifMessage = 'Status pengajuan surat ' . $letterType['name'] . ' Anda telah diubah menjadi ' . $status . '.';
            }
            
            $this->notificationModel->insert([
                'user_id' => $resident['user_id'],
                'title' => $notifTitle,
                'message' => $notifMessage,
                'link' => 'domicile-request/view/' . $id
            ]);
        }
        
        return redirect()->to(base_url('domicile-request'))->with('message', 'Status pengajuan surat berhasil diperbarui');
    }

    public function download($id)
    {
        // Check if user is admin or staff
        
        
        $request = $this->DomicileRequestModel->select('domicile_certificates.*,  residents.nik, residents.gender, residents.occupation, residents.religion')->join('residents', 'domicile_certificates.resident_id = residents.id')->where('domicile_certificates.id', $id)->where('domicile_certificates.status', 'IN(approved, completed)');

        if (session()->get('role') === 'resident') {
            $request = $request->where('residents.user_id', session()->get('user_id'));
        }
        $request = $request->first();

        
        if (!$request) {
            return redirect()->to(base_url('domicile-request'))->with('error', 'Pengajuan surat tidak ditemukan');
        }
        
        $letterType = $this->letterTypeModel->find($request['letter_type_id']);
        
        // Generate PDF based on letter type template
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->setPaper('A4', 'portrait');
        
        // Check if template file exists, otherwise use default
        $templateFile = 'default_letter';
        if (!empty($letterType['template']) && file_exists(APPPATH . 'Views/letter_templates/' . $letterType['template'] . '.php')) {
            $templateFile = $letterType['template'];
        }

        // var_dump($letterType);die;
        
        // Render the letter template with data
        $html = view('letter_templates/' . $templateFile, [
            'request' => $request,
            'letterType' => $letterType
        ]);
        
        $dompdf->loadHtml($html);
        $dompdf->render();
        
        // Generate filename
        $filename = strtolower(str_replace(' ', '_', $letterType['name'])) . '_' . $request['nik'] . '.pdf';
        
        // Stream the file
        return $dompdf->stream($filename, ['Attachment' => true]);
    }
    public function create()
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Get active letter types
        $letterTypes = $this->letterTypeModel->where('template', 'sk_domisili')->getActive();
        
        $data = [
            'title' => 'Buat SK Domisili',
            'letterTypes' => $letterTypes,
            'validation' => \Config\Services::validation(),
            'kades' => $this->settingModel->getByCategoryAndKey('struktur_organisasi', 'kepala_desa'),
        ];
        
        return view('letter_requests/domicile/create', $data);
    }

    public function store()
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Validate input
        $rules = [
            'nik' => 'required|exact_length[16]',
            'letter_type_id' => 'required',
            'kk' => 'required|max_length[16]',
            'name' => 'required|max_length[200]',
            'pob' => 'required|max_length[100]',
            'dob' => 'required|valid_date',
            'address' => 'required|max_length[200]',
            'nationality' => 'required|max_length[50]',
            'rt' => 'required|max_length[3]',
            'rw' => 'required|max_length[3]',
            'purpose' => 'required|max_length[200]',
            'village_head_name' => 'required|max_length[100]',
            'village_head_nip' => 'required|max_length[20]',
            'village_head_position' => 'required|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        // Get resident data
        $resident = $this->residentModel->where('nik', $this->request->getPost('nik'))->first();
        if (!$resident) {
            return redirect()->back()->withInput()->with('error', 'Data penduduk tidak ditemukan');
        }

        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Save letter request
            $letterRequestId = $this->DomicileRequestModel->insert([
                'resident_id' => $resident['id'],
                'letter_type_id' => $this->request->getPost('letter_type_id'),
                'kk' => $this->request->getPost('kk'),
                'name' => $this->request->getPost('name'),
                'pob' => $this->request->getPost('pob'),
                'dob' => $this->request->getPost('dob'),
                'address' => $this->request->getPost('address'),
                'nationality' => $this->request->getPost('nationality'),
                'rt' => $this->request->getPost('rt'),
                'rw' => $this->request->getPost('rw'),
                'purpose' => $this->request->getPost('purpose'),
                'description' => $this->request->getPost('description'),
                'valid_from' => date('Y-m-d'),
                'village_head_name' => $this->request->getPost('village_head_name'),
                'village_head_nip' => $this->request->getPost('village_head_nip'),
                'village_head_position' => $this->request->getPost('village_head_position'),
                'processed_by' => session()->get('user_id'),
                'status' => 'approved',
            ]);
            // var_dump($this->DomicileRequestModel->getLastQuery()->getQuery());
            
            if (!$letterRequestId) {
                // var_dump($this->validator->getErrors());
                // die;
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
            
            // echo $this->DomicileRequestModel->getLastQuery()->getQuery();
            $letterType = $this->letterTypeModel->find($this->request->getPost('letter_type_id'));
            $this->DomicileRequestModel->generateCertificateNumber($letterRequestId, $letterType['code']);
            
            // Handle document uploads
            $documentFiles = $this->request->getFiles();
            $documentNames = $this->request->getPost('document_names');
            $attachmentIds = $this->request->getPost('attachment_ids') ?? [];
            
            
            // Upload new documents if any
            if (isset($documentFiles['documents']) && is_array($documentFiles['documents'])) {
                foreach ($documentFiles['documents'] as $index => $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $documentName = $documentNames[$index] ?? 'Document ' . ($index + 1);
                        
                        // Generate unique filename
                        $newName = $file->getRandomName();
                        
                        // Move file to uploads directory
                        $file->move(ROOTPATH . 'public/uploads/documents', $newName);
                        
                        // Save to database
                        $this->attachmentModel->insert([
                            'letter_request_id' => $letterRequestId,
                            'letter_type_id' => $this->request->getPost('letter_type_id'),
                            'name' => $documentName,
                            'file_path' => 'uploads/documents/' . $newName,
                            'file_type' => $file->getClientMimeType(),
                            'file_size' => $file->getSize(),
                            'description' => 'Dokumen ' . $documentName . ' untuk pengajuan surat'
                        ]);   
                    }
                }
            }
            // die;

            // Create notification
            $resident = $this->residentModel->where('nik', $this->request->getPost('nik'))->first();
            if ($resident['user_id']) {
                $this->notificationModel->insert([
                    'user_id' => $resident['user_id'],
                    'title' => 'Pengajuan Surat Baru',
                    'message' => 'Pengajuan surat baru telah dibuat',
                    'type' => 'info',
                    'is_read' => 0
                ]);
            }

            // Commit transaction
            // var_dump($this->DomicileRequestModel->find($letterRequestId));
            $db->transComplete();

            return redirect()->to(base_url('domicile-request'))->with('message', 'Pengajuan surat berhasil dibuat');
        } catch (\Exception $e) {
            // Rollback transaction on error
            
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function createRequest()
    {
        // Check if user is resident
        if (session()->get('role') !== 'resident') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Get active letter types
        $letterTypes = $this->letterTypeModel->where('template', 'sk_domisili')->getActive();
        
        $data = [
            'title' => 'Pengajuan Pembuatan SK Domisili',
            'letterTypes' => $letterTypes,
            'resident' => $this->residentModel->where('user_id', session()->get('user_id'))->first(),
            'validation' => \Config\Services::validation(),
            'kades' => $this->settingModel->getByCategoryAndKey('struktur_organisasi', 'kepala_desa'),
        ];
        
        return view('letter_requests/domicile/create_request', $data);
    }

    public function storeRequest()
    {
        // Check if user is resident
        if (session()->get('role') !== 'resident') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Validate input
        $rules = [
            'nik' => 'required|exact_length[16]',
            'letter_type_id' => 'required',
            'kk' => 'required|max_length[16]',
            'name' => 'required|max_length[200]',
            'pob' => 'required|max_length[100]',
            'dob' => 'required|valid_date',
            'address' => 'required|max_length[50]',
            'nationality' => 'required|max_length[50]',
            'rt' => 'required|max_length[3]',
            'rw' => 'required|max_length[3]',
            'purpose' => 'required|max_length[200]',
            'village_head_name' => 'required|max_length[100]',
            'village_head_nip' => 'required|max_length[20]',
            'village_head_position' => 'required|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Get resident data
        $resident = $this->residentModel->where('nik', $this->request->getPost('nik'))->first();
        if (!$resident) {
            return redirect()->back()->withInput()->with('error', 'Data penduduk tidak ditemukan');
        }

        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $sigKades = get_setting('etc','ttd_kepala_desa', false);

            // Save letter request
            $letterRequestId = $this->DomicileRequestModel->insert([
                'resident_id' => $resident['id'],
                'letter_type_id' => $this->request->getPost('letter_type_id'),
                'kk' => $this->request->getPost('kk'),
                'name' => $this->request->getPost('name'),
                'pob' => $this->request->getPost('pob'),
                'dob' => $this->request->getPost('dob'),
                'address' => $this->request->getPost('address'),
                'nationality' => $this->request->getPost('nationality'),
                'rt' => $this->request->getPost('rt'),
                'rw' => $this->request->getPost('rw'),
                'purpose' => $this->request->getPost('purpose'),
                'description' => $this->request->getPost('description'),
                'valid_from' => date('Y-m-d'),
                'village_head_name' => $this->request->getPost('village_head_name'),
                'village_head_nip' => $this->request->getPost('village_head_nip'),
                'village_head_position' => $this->request->getPost('village_head_position'),
                'village_head_signature' => $sigKades,
                'status' => 'pending',
            ]);

           
            
            if (!$letterRequestId) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
            
            
            // Handle document uploads
            $documentFiles = $this->request->getFiles();
            $documentNames = $this->request->getPost('document_names');
            
            if (isset($documentFiles['documents']) && !empty($documentFiles['documents'])) {
                foreach ($documentFiles['documents'] as $index => $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $documentName = $documentNames[$index] ?? 'Document ' . ($index + 1);
                        // Generate unique filename
                        $newName = $file->getRandomName();
                        // Move file to uploads directory
                        $file->move(ROOTPATH . 'public/uploads/documents', $newName);
                        // Save to database
                       
                        $this->attachmentModel->insert([
                            'letter_request_id' => $letterRequestId,
                            'letter_type_id' => $this->request->getPost('letter_type_id'),
                            'name' => $documentName,
                            'file_path' => 'uploads/documents/' . $newName,
                            'file_type' => $file->getClientMimeType(),
                            'file_size' => $file->getSize(),
                            'description' => 'Dokumen ' . $documentName . ' untuk pengajuan surat'
                        ]);   
                        echo $this->attachmentModel->getLastQuery()->getQuery();
                    }
                }
            }
            // die;

            // Create notification
            if ($resident['user_id']) {
                $this->notificationModel->insert([
                    'user_id' => $resident['user_id'],
                    'title' => 'Pengajuan Surat Baru',
                    'message' => 'Pengajuan surat baru telah dibuat',
                    'type' => 'info',
                    'is_read' => 0
                ]);
            }

            // Commit transaction
            // var_dump($this->DomicileRequestModel->find($letterRequestId));
            $db->transComplete();

            return redirect()->to(base_url('domicile-request/my-request'))->with('message', 'Pengajuan surat berhasil dibuat');
        } catch (\Exception $e) {
            // Rollback transaction on error
            
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    function edit($id) {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Get the request data
        $request = $this->DomicileRequestModel->select('domicile_certificates.*, residents.nik, letter_types.name as letter_type_name, letter_types.code as letter_type_code, letter_types.id as letter_type_id, letter_types.required_documents')
        ->join('letter_types', 'letter_types.id = domicile_certificates.letter_type_id')
        ->join('residents', 'residents.id = domicile_certificates.resident_id')->find($id);
        if (!$request) {
            return redirect()->to(base_url('domicile-request'))->with('error', 'Pengajuan surat tidak ditemukan');
        }

        // Get letter type data
        $letterType = $this->letterTypeModel->find($request['letter_type_id']);
        
        
        // Get document attachments
        $attachments = $this->attachmentModel->getByLetterRequestId($id, $request['letter_type_id']);

        $data = [
            'title' => 'Edit Pengajuan Surat',
            'request' => $request,
            'letterType' => $letterType,
            'attachments' => $attachments,
            'validation' => \Config\Services::validation(),
            'kades' => $this->settingModel->getByCategoryAndKey('struktur_organisasi', 'kepala_desa'),
        ];

        return view('letter_requests/domicile/edit', $data);
    }

    public function update($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Validate input
        $rules = [
            'nik' => 'required|exact_length[16]',
            'letter_type_id' => 'required',
            'kk' => 'required|max_length[16]',
            'name' => 'required|max_length[200]',
            'pob' => 'required|max_length[100]',
            'dob' => 'required|valid_date',
            'address' => 'required|max_length[50]',
            'nationality' => 'required|max_length[50]',
            'rt' => 'required|max_length[3]',
            'rw' => 'required|max_length[3]',
            'purpose' => 'required|max_length[200]',
            'village_head_name' => 'required|max_length[100]',
            'village_head_nip' => 'required|max_length[20]',
            'village_head_position' => 'required|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get resident data
        $resident = $this->residentModel->where('nik', $this->request->getPost('nik'))->first();
        if (!$resident) {
            return redirect()->back()->withInput()->with('error', 'Data penduduk tidak ditemukan');
        }

        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Update letter request
            $this->DomicileRequestModel->update($id, [
                'resident_id' => $resident['id'],
                'letter_type_id' => $this->request->getPost('letter_type_id'),
                'kk' => $this->request->getPost('kk'),
                'name' => $this->request->getPost('name'),
                'pob' => $this->request->getPost('pob'),
                'dob' => $this->request->getPost('dob'),
                'address' => $this->request->getPost('address'),
                'nationality' => $this->request->getPost('nationality'),
                'rt' => $this->request->getPost('rt'),
                'rw' => $this->request->getPost('rw'),
                'purpose' => $this->request->getPost('purpose'),
                'description' => $this->request->getPost('description'),
                'valid_from' => date('Y-m-d'),
                'village_head_name' => $this->request->getPost('village_head_name'),
                'village_head_nip' => $this->request->getPost('village_head_nip'),
                'village_head_position' => $this->request->getPost('village_head_position'),
                'village_head_signature' => $this->request->getPost('village_head_signature'),
                'processed_by' => session()->get('user_id'),
            ]);

            // Handle document uploads
            $documentFiles = $this->request->getFiles();
            $documentNames = $this->request->getPost('document_names');
            $attachmentIds = $this->request->getPost('attachment_ids') ?? [];

            
            // Upload new documents if any
            if (isset($documentFiles['documents']) && is_array($documentFiles['documents'])) {
                foreach ($documentFiles['documents'] as $index => $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $documentName = $documentNames[$index] ?? 'Document ' . ($index + 1);

                        $existingAttachment = $this->attachmentModel->where('letter_request_id', $id)->where('name', $documentName)->first();
                        $newName = $file->getRandomName();
                        $file->move(ROOTPATH . 'public/uploads/documents', $newName);

                        if ($existingAttachment) {
                            $this->attachmentModel->update($existingAttachment['id'], [
                                'name' => $documentName,
                                'file_path' => 'uploads/documents/' . $newName,
                                'file_type' => $file->getClientMimeType(),
                                'file_size' => $file->getSize(),
                            ]);
                        }
                    }
                }
            }

            // Create notification
            if ($resident['user_id']) {
                $this->notificationModel->insert([
                    'user_id' => $resident['user_id'],
                    'title' => 'Pengajuan Surat Diperbarui',
                    'message' => 'Pengajuan surat Anda telah diperbarui',
                    'type' => 'info',
                    'is_read' => 0
                ]);
            }

            // Commit transaction
            $db->transComplete();

            return redirect()->to(base_url('domicile-request'))->with('message', 'Pengajuan surat berhasil diperbarui');
        } catch (\Exception $e) {
            // Rollback transaction on error
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    function editRequest($id) {
        // Check if user is resident
        if (session()->get('role') !== 'resident') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Get the request data
        $request = $this->DomicileRequestModel->select('domicile_certificates.*, residents.nik, letter_types.name as letter_type_name, letter_types.code as letter_type_code, letter_types.id as letter_type_id, letter_types.required_documents')
        ->join('letter_types', 'letter_types.id = domicile_certificates.letter_type_id')
        ->join('residents', 'residents.id = domicile_certificates.resident_id')
        ->where('residents.user_id', session()->get('user_id'))
        ->where('domicile_certificates.id', $id)
        ->first();
        if (!$request) {
            return redirect()->to(base_url('domicile-request'))->with('error', 'Pengajuan surat tidak ditemukan');
        }

        // Get letter type data
        $letterType = $this->letterTypeModel->find($request['letter_type_id']);
        
        
        // Get document attachments
        $attachments = $this->attachmentModel->getByLetterRequestId($id, $request['letter_type_id']);

        $data = [
            'title' => 'Edit Pengajuan Surat',
            'request' => $request,
            'letterType' => $letterType,
            'attachments' => $attachments,
            'validation' => \Config\Services::validation(),
            'kades' => $this->settingModel->getByCategoryAndKey('struktur_organisasi', 'kepala_desa'),
        ];

        return view('letter_requests/domicile/edit_request', $data);
    }

    public function updateRequest($id)
    {
        // Check if user is resident
        if (session()->get('role') !== 'resident') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Validate input
        $rules = [
            'nik' => 'required|exact_length[16]',
            'letter_type_id' => 'required',
            'kk' => 'required|max_length[16]',
            'name' => 'required|max_length[200]',
            'pob' => 'required|max_length[100]',
            'dob' => 'required|valid_date',
            'address' => 'required|max_length[50]',
            'nationality' => 'required|max_length[50]',
            'rt' => 'required|max_length[3]',
            'rw' => 'required|max_length[3]',
            'purpose' => 'required|max_length[200]',
            'village_head_name' => 'required|max_length[100]',
            'village_head_nip' => 'required|max_length[20]',
            'village_head_position' => 'required|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get resident data
        $resident = $this->residentModel->where('nik', $this->request->getPost('nik'))->first();
        if (!$resident) {
            return redirect()->back()->withInput()->with('error', 'Data penduduk tidak ditemukan');
        }

        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Update letter request
            $this->DomicileRequestModel->update($id, [
                'resident_id' => $resident['id'],
                'letter_type_id' => $this->request->getPost('letter_type_id'),
                'kk' => $this->request->getPost('kk'),
                'name' => $this->request->getPost('name'),
                'pob' => $this->request->getPost('pob'),
                'dob' => $this->request->getPost('dob'),
                'address' => $this->request->getPost('address'),
                'nationality' => $this->request->getPost('nationality'),
                'rt' => $this->request->getPost('rt'),
                'rw' => $this->request->getPost('rw'),
                'purpose' => $this->request->getPost('purpose'),
                'description' => $this->request->getPost('description'),
                'valid_from' => date('Y-m-d'),
                'village_head_name' => $this->request->getPost('village_head_name'),
                'village_head_nip' => $this->request->getPost('village_head_nip'),
                'village_head_position' => $this->request->getPost('village_head_position'),
                'village_head_signature' => $this->request->getPost('village_head_signature'),
            ]);

            // Handle document uploads
            $documentFiles = $this->request->getFiles();
            $documentNames = $this->request->getPost('document_names');
            $attachmentIds = $this->request->getPost('attachment_ids') ?? [];

            
            // Upload new documents if any
            if (isset($documentFiles['documents']) && is_array($documentFiles['documents'])) {
                foreach ($documentFiles['documents'] as $index => $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $documentName = $documentNames[$index] ?? 'Document ' . ($index + 1);

                        $existingAttachment = $this->attachmentModel->where('letter_request_id', $id)->where('name', $documentName)->first();
                        $newName = $file->getRandomName();
                        $file->move(ROOTPATH . 'public/uploads/documents', $newName);

                        if ($existingAttachment) {
                            $this->attachmentModel->update($existingAttachment['id'], [
                                'name' => $documentName,
                                'file_path' => 'uploads/documents/' . $newName,
                                'file_type' => $file->getClientMimeType(),
                                'file_size' => $file->getSize(),
                            ]);
                        }
                    }
                }
            }

            // Create notification
            if ($resident['user_id']) {
                $this->notificationModel->insert([
                    'user_id' => $resident['user_id'],
                    'title' => 'Pengajuan Surat Diperbarui',
                    'message' => 'Pengajuan surat Anda telah diperbarui',
                    'type' => 'info',
                    'is_read' => 0
                ]);
            }

            // Commit transaction
            $db->transComplete();

            return redirect()->to(base_url('domicile-request/my-request'))->with('message', 'Pengajuan surat berhasil diperbarui');
        } catch (\Exception $e) {
            // Rollback transaction on error
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }


    
    public function delete($id)
    {

        // Get the letter request
        $request = $this->DomicileRequestModel->join('residents', 'domicile_certificates.resident_id = residents.id')->where('domicile_certificates.id', $id);
        $url = 'domicile-request';
        if (session()->get('role') === 'resident') {
            $request = $request->where('user_id', session()->get('user_id'));
            $url .= '/my-request';
        }
        $request = $request->first();

        // Check if request exists
        if (!$request) {
            return redirect()->to(base_url($url))->with('error', 'Pengajuan surat tidak ditemukan');
        }

        // Delete the request
        $this->DomicileRequestModel->delete($id);

        // Create notification to admin
        if ($request['user_id']) {
            $this->notificationModel->insert([
                'user_id' => session()->get('user_id'),
                'title' => 'Pengajuan Surat Dihapus',
                'message' => 'Pengajuan surat dengan ID ' . $id . ' telah dihapus',
                'type' => 'warning',
                'is_read' => 0
            ]);

        }

        return redirect()->to(base_url($url))->with('message', 'Pengajuan surat berhasil dihapus');
    }
} 