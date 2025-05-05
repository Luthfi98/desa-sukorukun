<?php

namespace App\Controllers\LetterRequest;

use App\Controllers\BaseController;
use App\Models\DeathCertificateModel;
use App\Models\LetterTypeModel;
use App\Models\ResidentModel;
use App\Models\NotificationModel;
use App\Models\SettingModel;
use App\Models\DocumentAttachmentModel;

class DeathCertificateRequestController extends BaseController
{
    protected $DeathCertificateModel;
    protected $letterTypeModel;
    protected $residentModel;
    protected $notificationModel;
    protected $settingModel;
    protected $attachmentModel;
    
    public function __construct()
    {
        $this->DeathCertificateModel = new DeathCertificateModel();
        $this->letterTypeModel = new LetterTypeModel();
        $this->residentModel = new ResidentModel();
        $this->notificationModel = new NotificationModel();
        $this->settingModel = new SettingModel();
        $this->attachmentModel = new DocumentAttachmentModel();
    }
    
    public function index()
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $status = $this->request->getGet('status') ?? 'pending';
        
        $data = [
            'title' => 'Pengajuan Surat Keterangan Kematian',
            'status' => $status,
            'requests' => $this->DeathCertificateModel->getByStatus($status),
            'letterTypes' => $this->letterTypeModel->where('template', 'sk_kematian')->getActive()
        ];
        
        return view('letter_requests/death/index', $data);
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
            'title' => 'Pengajuan Surat Keterangan Kematian',
            'status' => $status,
            'requests' => $this->DeathCertificateModel->where('resident_id', $resident['id'])->orderBy('created_at', 'desc')->getByStatus($status),
            'letterTypes' => $this->letterTypeModel->where('template', 'sk_kematian')->getActive()
        ];
        
        return view('letter_requests/death/my_request', $data);
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
        $builder = $db->table('death_certificates');
        $builder->select('death_certificates.*, letter_types.name as letter_type_name, letter_types.code as letter_type_code');
        $builder->join('letter_types', 'letter_types.id = death_certificates.letter_type_id');
        $builder->join('residents', 'residents.id = death_certificates.resident_id', 'left');
        $builder->where('death_certificates.status', $status);
        $builder->where('death_certificates.deleted_at', null);
        $url = 'death-cetificate-request';
        if (session()->get('role') === 'resident') {
            $builder->where('death_certificates.created_by', session()->get('user_id'));
            $url = 'death-cetificate-request/my-request';
        }
        
        // Apply letter type filter
        if (!empty($letterTypeId)) {
            $builder->where('death_certificates.letter_type_id', $letterTypeId);
        }
        
        // Apply date range filter
        if (!empty($dateStart)) {
            $builder->where('DATE(death_certificates.created_at) >=', $dateStart);
        }
        if (!empty($dateEnd)) {
            $builder->where('DATE(death_certificates.created_at) <=', $dateEnd);
        }
        
        // Get total records count
        $totalRecords = $builder->countAllResults(false);
        
        // Apply search filter if provided
        if (!empty($search)) {
            $builder->groupStart()
                ->like('death_certificates.name', $search)
                ->like('death_certificates.report_name', $search)
                ->orLike('death_certificates.nik', $search)
                ->orLike('death_certificates.report_nik', $search)
                ->orLike('letter_types.name', $search)
                ->orLike('death_certificates.number', $search)
                ->groupEnd();
        }
        
        // Get filtered records count
        $filteredRecords = $builder->countAllResults(false);
        
        // Apply ordering
        $orderColumn = 'death_certificates.created_at';
        $orderDir = 'DESC';
        
        if (!empty($order)) {
            $columns = [
                0 => 'death_certificates.created_at',
                1 => 'death_certificates.number',
                2 => 'death_certificates.report_name',
                3 => 'death_certificates.name',
                4 => 'letter_types.name'
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

            if ($status === 'pending' && (session()->get('role') !== 'resident' || $row['created_by'] === session()->get('user_id'))) {
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
            // $purpose = $row['purpose'];
            // if (strlen($purpose) > 30) {
            //     $purpose = substr($purpose, 0, 30) . '...';
            // }
            
            $formattedData[] = [
                date('d-m-Y', strtotime($row['created_at'])),
                $row['number'] ?? '<span class="text-muted">-</span>',
                $row['report_name'],
                $row['name'],
                $row['letter_type_name'],
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
        $request = $this->DeathCertificateModel->select('death_certificates.*, residents.occupation')->join('residents', 'death_certificates.resident_id = residents.id', 'left');
        $url = 'death-cetificate-request';
        if (session()->get('role') === 'resident') {
            $request = $request->where('residents.user_id', session()->get('user_id'));
            $url .= '/my-request';
        }
        $request = $request->find($id);
        
        if (!$request) {
            return redirect()->to(base_url('death-cetificate-request'))->with('error', 'Surat tidak ditemukan');
        }
        
        // Get letter type name
        $letterType = $this->letterTypeModel->find($request['letter_type_id']);
        $request['letter_type_name'] = $letterType['name'];
        $attachments = $this->attachmentModel->getByLetterRequestId($id, $request['letter_type_id']);
        $data = [
            'title' => 'Detail Surat Keterangan Kematian',
            'letterType' => $letterType,
            'request' => $request,
            'attachments' => $attachments,
            'url' => $url
        ];
        
        return view('letter_requests/death/show', $data);
    }

    public function process($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $request = $this->DeathCertificateModel->find($id);
        
        if (!$request) {
            return redirect()->to(base_url('death-cetificate-request'))->with('error', 'Pengajuan surat tidak ditemukan');
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
        
        return view('letter_requests/death/process', $data);
    }

    public function updateStatus($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $request = $this->DeathCertificateModel->find($id);
        
        if (!$request) {
            return redirect()->to(base_url('death-cetificate-request'))->with('error', 'Pengajuan surat tidak ditemukan');
        }
        
        $status = $this->request->getPost('status');
        $rejectionReason = $this->request->getPost('rejection_reason');
        
        // Process the request
        $this->DeathCertificateModel->processRequest($id, session()->get('user_id'), $status, $rejectionReason);
        
        // Generate reference number if approved
        if (($status === 'approved' || $status === 'completed') && empty($request['number'])) {
            $letterType = $this->letterTypeModel->find($request['letter_type_id']);
            $this->DeathCertificateModel->generateCertificateNumber($id, $letterType['code']);
        }
        
        // Send notification to resident
        $resident = $this->residentModel->select('residents.*, users.email')->join('users', 'residents.user_id = users.id', 'left')->find($request['resident_id']);

        // var_dump($resident);die;
        if ($resident && !empty($resident['user_id'])) {
            $letterType = $this->letterTypeModel->find($request['letter_type_id']);
            $notifTitle = 'Pengajuan Surat ' . $letterType['name'];
            
            $fileDocument = null;
            if ($status === 'approved') {
                
                $notifMessage = 'Pengajuan surat ' . $letterType['name'] . ' Anda telah disetujui dan sedang dalam proses pembuatan.';
                $fileDocument = $this->download($id, true);
            } elseif ($status === 'rejected') {
                $notifMessage = 'Pengajuan surat ' . $letterType['name'] . ' Anda ditolak. Alasan: ' . $rejectionReason;
               
            } elseif ($status === 'completed') {
                $fileDocument = $this->download($id, true);
                $notifMessage = 'Surat ' . $letterType['name'] . ' Anda telah selesai diproses dan siap untuk diambil.';
            } else {
                $notifMessage = 'Status pengajuan surat ' . $letterType['name'] . ' Anda telah diubah menjadi ' . $status . '.';
            }
            $msg = "
                    <p>Yth. Bapak/Ibu/Saudara,</p>
                    <p>".$notifMessage."</p>
                    <p>Terima kasih atas kesabaran Anda.</p>
                ";
            send_email($resident['email'], $letterType['name'], $msg, $fileDocument);
            
            $this->notificationModel->insert([
                'user_id' => $resident['user_id'],
                'title' => $notifTitle,
                'message' => $notifMessage,
                'link' => 'death-cetificate-request/view/' . $id
            ]);
        }
        
        return redirect()->to(base_url('death-cetificate-request'))->with('message', 'Status pengajuan surat berhasil diperbarui');
    }

    public function download($id, $save = false)
    {
        
        $request = $this->DeathCertificateModel->select('death_certificates.*')
            ->join('residents', 'death_certificates.resident_id = residents.id', 'left')
            ->where('death_certificates.id', $id);
        $url = 'death-cetificate-request';
        if (session()->get('role') === 'resident') {
            $request = $request->where('residents.user_id', session()->get('user_id'));
            $url .= '/my-request';
        }
        $request = $request->first();

        
        if (!$request) {
            return redirect()->to(base_url($url))->with('error', 'Pengajuan surat tidak ditemukan');
        }
        if ($request['status'] !== 'approved' && $request['status'] !== 'completed') {
            return redirect()->to(base_url($url))->with('error', 'Surat belum selesai diproses');
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
        $filename = 'surat_' . strtolower(str_replace(' ', '_', $letterType['name'])) . '_' . $request['nik'] . '.pdf';
        if ($save) {
            $path = ROOTPATH . 'public/pdf/' . $filename;
    
            file_put_contents($path, $dompdf->output());

            // var_dump(is_file($path));die;

            return $path;
        }else{
        
        // Stream the file
            return $dompdf->stream($filename, ['Attachment' => true]);
        }
    }
    public function create()
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            // return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Get active letter types
        $letterTypes = $this->letterTypeModel->where('template', 'sk_kematian')->getActive();
        
        $data = [
            'title' => 'Buat Surat Keterangan Kematian',
            'letterTypes' => $letterTypes,
            'validation' => \Config\Services::validation(),
            'kades' => $this->settingModel->getByCategoryAndKey('struktur_organisasi', 'kepala_desa'),
        ];
        
        return view('letter_requests/death/create', $data);
    }

    public function store()
    {
        // Check if user is admin or staff
        $processedBy = session()->get('user_id');
        $status = 'approved';
        $url = 'death-cetificate-request';
        $letterDate = $this->request->getPost('letter_date');
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            $status = 'pending';
            $processedBy = null;
            $letterDate = null;
            $url .= '/my-request';
            // return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Validate input
        $rules = [
            'report_nik' => 'required|exact_length[16]',
            'report_name' => 'required',
            'report_occupation' => 'required',
            'report_dob' => 'required',
            'relation' => 'required',
            'name' => 'required',
            // 'nik' => 'required|exact_length[16]',
            'report_address' => 'required',
            'pob' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'religion' => 'required',
            'address' => 'required',
            'death_date' => 'required',
            'location' => 'required',
            'reason' => 'required',
            // 'letter_date' => 'required',
            'letter_type_id' => 'required',
            'village_head_name' => 'required',
            'village_head_nip' => 'required',
            'village_head_position' => 'required'
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get resident data
        $resident = $this->residentModel->where('nik', $this->request->getPost('nik'))->first();

        // if (!$resident) {
        //     return redirect()->back()->withInput()->with('error', 'Data penduduk tidak ditemukan');
        // }

        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Save letter request
            $data = [
                'resident_id' => $resident['id']??null,
                'letter_type_id' => $this->request->getPost('letter_type_id'),
                'report_nik' => $this->request->getPost('report_nik'),
                'report_name' => $this->request->getPost('report_name'),
                'report_occupation' => $this->request->getPost('report_occupation'),
                'report_dob' => $this->request->getPost('report_dob'),
                'relation' => $this->request->getPost('relation'),
                'name' => $this->request->getPost('name'),
                'nik' => $this->request->getPost('nik'),
                'report_address' => $this->request->getPost('report_address'),
                'pob' => $this->request->getPost('pob'),
                'dob' => $this->request->getPost('dob'),
                'gender' => $this->request->getPost('gender'),
                'religion' => $this->request->getPost('religion'),
                'address' => $this->request->getPost('address'),
                'death_date' => $this->request->getPost('death_date'),
                'location' => $this->request->getPost('location'),
                'reason' => $this->request->getPost('reason'),
                'letter_date' => $letterDate,
                'village_head_name' => $this->request->getPost('village_head_name'),
                'village_head_nip' => $this->request->getPost('village_head_nip'),
                'village_head_position' => $this->request->getPost('village_head_position'),
                'processed_by' => $processedBy,
                'status' => $status,
                'created_by' => session()->get('user_id')
            ];
            $letterRequestId = $this->DeathCertificateModel->insert($data);

            if (!$letterRequestId) {
                $errors = $this->DeathCertificateModel->errors();
                return redirect()->back()->withInput()->with('errors', $errors);
            }
            $letterType = $this->letterTypeModel->find($this->request->getPost('letter_type_id'));
            if ($status == 'approved') {
                $this->DeathCertificateModel->generateCertificateNumber($letterRequestId, $letterType['code']);
            }
            // var_dump($letterType);die;

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

            // Create notification
            if (isset($resident['user_id'])) {
                $this->notificationModel->insert([
                    'user_id' => $resident['user_id'],
                    'title' => 'Pengajuan Surat Baru',
                    'message' => 'Pengajuan surat baru telah dibuat',
                    'type' => 'info',
                    'is_read' => 0
                ]);
                if ($status != 'pending') {
                    $msg = "
                            <p>Yth. Bapak/Ibu/Saudara,</p>
                            <p>Surat keterangan kematian telah dibuat.</p>
                            <p>Terima kasih atas kesabaran Anda.</p>
                        ";
                    send_email($resident['email'], $letterType['name'], $msg, $this->download($letterRequestId,true));
                }

                
            }

            // Commit transaction
            $db->transComplete();

            return redirect()->to(base_url($url))->with('message', 'Pengajuan surat berhasil dibuat');
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
        $letterTypes = $this->letterTypeModel->where('template', 'sk_kematian')->getActive();
        
        $data = [
            'title' => 'Pengajuan Pembuatan Surat Keterangan Kematian',
            'letterTypes' => $letterTypes,
            'resident' => $this->residentModel->where('user_id', session()->get('user_id'))->first(),
            'validation' => \Config\Services::validation(),
            'kades' => $this->settingModel->getByCategoryAndKey('struktur_organisasi', 'kepala_desa'),
        ];
        
        return view('letter_requests/death/create_request', $data);
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
            'purpose' => 'required|min_length[10]',
            // 'village_head_name' => 'required',
            // 'village_head_position' => 'required',
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
            $letterRequestId = $this->DeathCertificateModel->insert([
                'resident_id' => $resident['id'],
                'letter_type_id' => $this->request->getPost('letter_type_id'),
                'name' => $this->request->getPost('name'),
                'pob' => $this->request->getPost('pob'),
                'dob' => $this->request->getPost('dob'),
                'religion' => $this->request->getPost('religion'),
                'nationality' => $this->request->getPost('nationality'),
                'rt' => $this->request->getPost('rt'),
                'rw' => $this->request->getPost('rw'),
                'purpose' => $this->request->getPost('purpose'),
                'description' => $this->request->getPost('description'),
                'valid_from' => $this->request->getPost('valid_from'),
                'village_head_name' => $this->request->getPost('village_head_name'),
                'village_head_nip' => $this->request->getPost('village_head_nip'),
                'village_head_position' => $this->request->getPost('village_head_position'),
                // 'processed_by' => session()->get('user_id'),
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
            // var_dump($this->DeathCertificateModel->find($letterRequestId));
            $db->transComplete();

            return redirect()->to(base_url('death-cetificate-request/my-request'))->with('message', 'Pengajuan surat berhasil dibuat');
        } catch (\Exception $e) {
            // Rollback transaction on error
            
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    function edit($id) {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            // return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Get the request data
        $request = $this->DeathCertificateModel->select('death_certificates.*, letter_types.name as letter_type_name, letter_types.code as letter_type_code, letter_types.id as letter_type_id, letter_types.required_documents')
        ->join('letter_types', 'letter_types.id = death_certificates.letter_type_id')
        ->join('residents', 'residents.id = death_certificates.resident_id', 'left')->find($id);
        if (!$request) {
            return redirect()->to(base_url('death-cetificate-request'))->with('error', 'Pengajuan surat tidak ditemukan');
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

        return view('letter_requests/death/edit', $data);
    }

    public function update($id)
    {
        // Check if user is admin or staff
        $url = 'death-cetificate-request';
        $letterDate = null;
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            $url .= '/my-request';
            // return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }else{
            $processedBy = session()->get('user_id');
            $letterDate = $this->request->getPost('letter_date');
        }

        // Validate input
        $rules = [
            'report_nik' => 'required|exact_length[16]',
            'report_name' => 'required',
            'report_occupation' => 'required',
            'report_dob' => 'required',
            'relation' => 'required',
            'name' => 'required',
            'report_address' => 'required',
            'pob' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'religion' => 'required',
            'address' => 'required',
            'death_date' => 'required',
            'location' => 'required',
            'reason' => 'required',
            // 'letter_date' => 'required',
            'letter_type_id' => 'required',
            'village_head_name' => 'required',
            'village_head_nip' => 'required',
            'village_head_position' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get resident data
        $resident = $this->residentModel->where('nik', $this->request->getPost('nik'))->first();
        // if (!$resident) {
        //     return redirect()->back()->withInput()->with('error', 'Data penduduk tidak ditemukan');
        // }

        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $data = [
                'resident_id' => $resident['id']??null,
                'letter_type_id' => $this->request->getPost('letter_type_id'),
                'report_nik' => $this->request->getPost('report_nik'),
                'report_name' => $this->request->getPost('report_name'),
                'report_occupation' => $this->request->getPost('report_occupation'),
                'report_dob' => $this->request->getPost('report_dob'),
                'relation' => $this->request->getPost('relation'),
                'name' => $this->request->getPost('name'),
                'nik' => $this->request->getPost('nik'),
                'report_address' => $this->request->getPost('report_address'),
                'pob' => $this->request->getPost('pob'),
                'dob' => $this->request->getPost('dob'),
                'gender' => $this->request->getPost('gender'),
                'religion' => $this->request->getPost('religion'),
                'address' => $this->request->getPost('address'),
                'death_date' => $this->request->getPost('death_date'),
                'location' => $this->request->getPost('location'),
                'reason' => $this->request->getPost('reason'),
                'letter_date' => $letterDate,
                'village_head_name' => $this->request->getPost('village_head_name'),
                'village_head_nip' => $this->request->getPost('village_head_nip'),
                'village_head_position' => $this->request->getPost('village_head_position'),
            ];
            
            if (isset($processedBy)) {
                $data['processed_by'] = $processedBy;
            }
            // var_dump($data);die;
            // Update letter request
            $this->DeathCertificateModel->update($id, $data);

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
                        } else {
                            $this->attachmentModel->insert([
                                'letter_request_id' => $id,
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
            }

            // Create notification
            if (isset($resident['user_id'])) {
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

            return redirect()->to(base_url($url))->with('message', 'Pengajuan surat berhasil diperbarui');
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
        $request = $this->DeathCertificateModel->select('death_certificates.*, letter_types.name as letter_type_name, letter_types.code as letter_type_code, letter_types.id as letter_type_id, letter_types.required_documents')
        ->join('letter_types', 'letter_types.id = death_certificates.letter_type_id')
        ->join('residents', 'residents.id = death_certificates.resident_id')
        ->where('residents.user_id', session()->get('user_id'))
        ->where('death_certificates.id', $id)
        ->first();
        if (!$request) {
            return redirect()->to(base_url('death-cetificate-request/my-request'))->with('error', 'Pengajuan surat tidak ditemukan');
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

        return view('letter_requests/death/edit_request', $data);
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
            'purpose' => 'required|min_length[10]',
            'name' => 'required',
            'pob' => 'required',
            'dob' => 'required',
            'religion' => 'required',
            'nationality' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'village_head_name' => 'required',
            'village_head_position' => 'required'
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
            $this->DeathCertificateModel->update($id, [
                'resident_id' => $resident['id'],
                'letter_type_id' => $this->request->getPost('letter_type_id'),
                'name' => $this->request->getPost('name'),
                'pob' => $this->request->getPost('pob'),
                'dob' => $this->request->getPost('dob'),
                'religion' => $this->request->getPost('religion'),
                'nationality' => $this->request->getPost('nationality'),
                'rt' => $this->request->getPost('rt'),
                'rw' => $this->request->getPost('rw'),
                'purpose' => $this->request->getPost('purpose'),
                'description' => $this->request->getPost('description'),
                'village_head_name' => $this->request->getPost('village_head_name'),
                'village_head_nip' => $this->request->getPost('village_head_nip'),
                'village_head_position' => $this->request->getPost('village_head_position'),
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

            return redirect()->to(base_url('death-cetificate-request/my-request'))->with('message', 'Pengajuan surat berhasil diperbarui');
        } catch (\Exception $e) {
            // Rollback transaction on error
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }


    
    public function delete($id)
    {

        // Get the letter request
        $request = $this->DeathCertificateModel->join('residents', 'death_certificates.resident_id = residents.id')->where('death_certificates.id', $id);
        $url = 'death-cetificate-request';
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
        $this->DeathCertificateModel->delete($id);

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