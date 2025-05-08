<?php

namespace App\Controllers\LetterRequest;

use App\Controllers\BaseController;
use App\Models\HeirRequestModel;
use App\Models\LetterTypeModel;
use App\Models\ResidentModel;
use App\Models\NotificationModel;
use App\Models\SettingModel;
use App\Models\DocumentAttachmentModel;

class HeirRequestController extends BaseController
{
    protected $HeirRequestModel;
    protected $letterTypeModel;
    protected $residentModel;
    protected $notificationModel;
    protected $settingModel;
    protected $attachmentModel;
    
    public function __construct()
    {
        $this->HeirRequestModel = new HeirRequestModel();
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
            'title' => 'Pengajuan SK Ahli Waris',
            'status' => $status,
            'requests' => $this->HeirRequestModel->getByStatus($status),
            'letterTypes' => $this->letterTypeModel->where('template', 'sk_ahli_waris')->getActive()
        ];
        
        return view('letter_requests/heir/index', $data);
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
            'title' => 'Pengajuan SK Ahli Waris',
            'status' => $status,
            'requests' => $this->HeirRequestModel->where('resident_id', $resident['id'])->orderBy('created_at', 'desc')->getByStatus($status),
            'letterTypes' => $this->letterTypeModel->where('template', 'sk_ahli_waris')->getActive()
        ];
        
        return view('letter_requests/heir/my_request', $data);
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
        $builder = $db->table('heir_certificates');
        $builder->select('heir_certificates.*, letter_types.name as letter_type_name, letter_types.code as letter_type_code, residents.name as resident_name, residents.nik as resident_nik, residents.user_id');
        $builder->join('letter_types', 'letter_types.id = heir_certificates.letter_type_id');
        $builder->join('residents', 'residents.id = heir_certificates.resident_id');
        $builder->where('heir_certificates.status', $status);
        $builder->where('heir_certificates.deleted_at', null);
        $url = 'heir-request';
        if (session()->get('role') === 'resident') {
            $builder->where('residents.user_id', session()->get('user_id'));
            $url = 'heir-request/my-request';
        }
        
        // Apply letter type filter
        if (!empty($letterTypeId)) {
            $builder->where('heir_certificates.letter_type_id', $letterTypeId);
        }
        
        // Apply date range filter
        if (!empty($dateStart)) {
            $builder->where('DATE(heir_certificates.created_at) >=', $dateStart);
        }
        if (!empty($dateEnd)) {
            $builder->where('DATE(heir_certificates.created_at) <=', $dateEnd);
        }
        
        // Get total records count
        $totalRecords = $builder->countAllResults(false);
        
        // Apply search filter if provided
        if (!empty($search)) {
            $builder->groupStart()
                ->like('residents.name', $search)
                ->orLike('residents.nik', $search)
                ->orLike('letter_types.name', $search)
                ->orLike('heir_certificates.number', $search)
                ->groupEnd();
        }
        
        // Get filtered records count
        $filteredRecords = $builder->countAllResults(false);
        
        // Apply ordering
        $orderColumn = 'heir_certificates.created_at';
        $orderDir = 'DESC';
        
        if (!empty($order)) {
            $columns = [
                0 => 'heir_certificates.created_at',
                1 => 'residents.name',
                2 => 'residents.nik',
                3 => 'letter_types.name',
                4 => 'heir_certificates.number'
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

            if ($status === 'pending' && (session()->get('role') !== 'resident' || $row['user_id'] === session()->get('user_id'))) {
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
            
            $formattedData[] = [
                date('d-m-Y', strtotime($row['created_at'])),
                $row['resident_name'],
                $row['resident_nik'],
                $row['letter_type_name'],
                $row['number'] ?? '<span class="text-muted">-</span>',
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
        $request = $this->HeirRequestModel->select('heir_certificates.*,  residents.nik, residents.gender, residents.occupation, letter_types.name as letter_type_name, users.name as processor_name')->join('residents', 'heir_certificates.resident_id = residents.id')
            ->join('letter_types', 'heir_certificates.letter_type_id = letter_types.id', 'left')
            ->join('users', 'heir_certificates.processed_by = users.id', 'left');
        $url = 'heir-request';
        if (session()->get('role') === 'resident') {
            $request = $request->where('residents.user_id', session()->get('user_id'));
            $url .= '/my-request';
        }
        $request = $request->find($id);
        
        if (!$request) {
            return redirect()->to(base_url($url))->with('error', 'Surat tidak ditemukan');
        }
        
        // Get letter type name
        $attachments = $this->attachmentModel->getByLetterRequestId($id, $request['letter_type_id']);
        $data = [
            'title' => 'Detail SK Ahli Waris',
            'request' => $request,
            'attachments' => $attachments,
            'url' => $url
        ];
        
        return view('letter_requests/heir/show', $data);
    }

    public function process($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $request = $this->HeirRequestModel->find($id);
        
        if (!$request) {
            return redirect()->to(base_url('heir-request'))->with('error', 'Pengajuan surat tidak ditemukan');
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
        
        return view('letter_requests/heir/process', $data);
    }

    public function updateStatus($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $request = $this->HeirRequestModel->find($id);
        
        if (!$request) {
            return redirect()->to(base_url('heir-request'))->with('error', 'Pengajuan surat tidak ditemukan');
        }
        
        $status = $this->request->getPost('status');
        $rejectionReason = $this->request->getPost('rejection_reason');
        
        // Process the request
        $this->HeirRequestModel->processRequest($id, session()->get('user_id'), $status, $rejectionReason);
        
        // Generate reference number if approved
        if (($status === 'approved' || $status === 'completed') && empty($request['number'])) {
            $letterType = $this->letterTypeModel->find($request['letter_type_id']);
            $this->HeirRequestModel->generateCertificateNumber($id, $letterType['code']);
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
                'link' => 'heir-request/view/' . $id
            ]);
        }
        
        return redirect()->to(base_url('heir-request'))->with('message', 'Status pengajuan surat berhasil diperbarui');
    }

    public function download($id, $save = false)
    {
        
        $request = $this->HeirRequestModel->select('heir_certificates.*,  residents.nik, residents.gender, residents.occupation, heir_certificates.address')
            ->join('residents', 'heir_certificates.resident_id = residents.id')
            ->where('heir_certificates.id', $id);
        $url = 'heir-request';
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
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Get active letter types
        $letterTypes = $this->letterTypeModel->where('template', 'sk_ahli_waris')->getActive();
        
        $data = [
            'title' => 'Buat SK Ahli Waris',
            'letterTypes' => $letterTypes,
            'validation' => \Config\Services::validation(),
            'kades' => $this->settingModel->getByCategoryAndKey('struktur_organisasi', 'kepala_desa'),
            'camat' => $this->settingModel->getByCategoryAndKey('struktur_organisasi', 'kepala_camat'),
        ];
        
        return view('letter_requests/heir/create', $data);
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
            'date_of_death' => 'required|valid_date',
            'time_of_death' => 'required',
            'place_of_death' => 'required',
            'cause_of_death' => 'required',
            'burial_location' => 'required',
            'burial_date' => 'required|valid_date',
            'heir_names' => 'required|is_array',
            'heir_birth_places' => 'required|is_array',
            'heir_birth_dates' => 'required|is_array',
            'heir_relationships' => 'required|is_array',
            'heir_niks' => 'required|is_array',
            'heir_reporter' => 'required|is_array'
        ];

        
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Get resident data
        $resident = $this->residentModel->select('residents.*, users.email')->join('users', 'residents.user_id = users.id', 'left')->where('nik', $this->request->getPost('nik'))->first();

        if (!$resident) {
            return redirect()->back()->withInput()->with('error', 'Data penduduk tidak ditemukan');
        }
        
        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Prepare heir data
            $heirData = [];
            $heirNames = $this->request->getPost('heir_names');
            $heirBirthPlaces = $this->request->getPost('heir_birth_places');
            $heirBirthDates = $this->request->getPost('heir_birth_dates');
            $heirRelationships = $this->request->getPost('heir_relationships');
            $heirNiks = $this->request->getPost('heir_niks');
            $heirReporters = $this->request->getPost('heir_reporter');

            foreach ($heirNames as $index => $name) {
                $heirData[] = [
                    'name' => $name,
                    'birth_place' => $heirBirthPlaces[$index],
                    'birth_date' => $heirBirthDates[$index],
                    'relationship' => $heirRelationships[$index],
                    'nik' => $heirNiks[$index],
                    'is_reporter' => isset($heirReporters[$index]) ? 1 : 0
                ];
            }

        // var_dump($heirData);die;

            
            $kades = get_setting('struktur_organisasi','kepala_desa', false);
            $sigKades = get_setting('etc','ttd_kepala_desa', NULL);
            // var_dump($sigKades);die;
            $camat = get_setting('struktur_organisasi','kepala_camat', false);
            $sigCamat = get_setting('etc','ttd_camat', NULL);
            // Save letter request
            $letterRequestId = $this->HeirRequestModel->insert([
                'resident_id' => $resident['id'],
                'letter_type_id' => $this->request->getPost('letter_type_id'),
                'name' => $this->request->getPost('name'),
                'pob' => $this->request->getPost('pob'),
                'dob' => $this->request->getPost('dob'),
                'religion' => $this->request->getPost('religion'),
                'occupation' => $this->request->getPost('occupation'),
                'address' => $this->request->getPost('address'),
                'date_of_death' => $this->request->getPost('date_of_death'),
                'time_of_death' => $this->request->getPost('time_of_death'),
                'place_of_death' => $this->request->getPost('place_of_death'),
                'cause_of_death' => $this->request->getPost('cause_of_death'),
                'burial_location' => $this->request->getPost('burial_location'),
                'burial_date' => $this->request->getPost('burial_date'),
                'heir_data' => json_encode($heirData),
                'processed_by' => session()->get('user_id'),
                'status' => 'approved',
                'village_head_name' => $this->request->getPost('village_head_name'),
                'village_head_nip' => $this->request->getPost('village_head_nip'),
                'village_head_position' => $this->request->getPost('village_head_position'),
                'village_head_signature' => $sigKades,
                'subdistrict_head_name' => $this->request->getPost('subdistrict_head_name'),
                'subdistrict_head_nip' => $this->request->getPost('subdistrict_head_nip'),
                'subdistrict_head_position' => $this->request->getPost('subdistrict_head_position'),
                'subdistrict_head_signature' => $sigCamat,
            ]);
            
            if (!$letterRequestId) {
                return redirect()->back()->withInput()->with('errors', $this->HeirRequestModel->getErrors());
            }
            
            $letterType = $this->letterTypeModel->find($this->request->getPost('letter_type_id'));
            $this->HeirRequestModel->generateCertificateNumber($letterRequestId, $letterType['code']);
            
            // Handle document uploads
            $documentFiles = $this->request->getFiles();
            $documentNames = $this->request->getPost('document_names');
            // var_dump($documentFiles);die;

            if (isset($documentFiles['documents']) && !empty($documentFiles['documents'])) {
                foreach ($documentFiles['documents'] as $index => $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $documentName = $documentNames[$index] ?? 'Document ' . ($index + 1);
                        $newName = $file->getRandomName();
                        $file->move(ROOTPATH . 'public/uploads/documents', $newName);

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

            if (isset($resident['user_id'])) {
                $this->notificationModel->insert([
                    'user_id' => $resident['user_id'],
                    'title' => 'Pengajuan Surat Baru',
                    'message' => 'Pengajuan surat baru telah dibuat',
                    'type' => 'info',
                    'is_read' => 0
                ]);

                $msg = "
                    <p>Yth. Bapak/Ibu/Saudara,</p>
                    <p>Berkas <strong>".$letterType['name']."</strong> telah dibuat, berikut filenya kami lampirkan.</p>
                    <p>Terima kasih atas kesabaran Anda.</p>
                ";
                
                send_email($resident['email'], $letterType['name'], $msg, $this->download($letterRequestId, true));
            }

            $db->transComplete();
            return redirect()->to(base_url('heir-request'))->with('message', 'Pengajuan surat berhasil dibuat');
        } catch (\Exception $e) {
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
        $letterTypes = $this->letterTypeModel->where('template', 'sk_ahli_waris')->getActive();
        
        $data = [
            'title' => 'Pengajuan Pembuatan SK Ahli Waris',
            'letterTypes' => $letterTypes,
            'resident' => $this->residentModel->where('user_id', session()->get('user_id'))->first(),
            'validation' => \Config\Services::validation(),
            'kades' => $this->settingModel->getByCategoryAndKey('struktur_organisasi', 'kepala_desa'),
            'camat' => $this->settingModel->getByCategoryAndKey('struktur_organisasi', 'kepala_camat'),
        ];
        
        return view('letter_requests/heir/create_request', $data);
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
            'date_of_death' => 'required|valid_date',
            'time_of_death' => 'required',
            'place_of_death' => 'required',
            'cause_of_death' => 'required',
            'burial_location' => 'required',
            'burial_date' => 'required|valid_date',
            'heir_names' => 'required',
            'heir_birth_places' => 'required',
            'heir_birth_dates' => 'required',
            'heir_relationships' => 'required',
            'heir_niks' => 'required',
            'heir_reporter' => 'required'
        ];

        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Get resident data
        $resident = $this->residentModel->where('nik', $this->request->getPost('nik'))->first();
        if (!$resident) {
            return redirect()->back()->withInput()->with('error', 'Data penduduk tidak ditemukan');
        }
        // var_dump($resident);die;

        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Prepare heir data
            $heirData = [];
            $heirNames = $this->request->getPost('heir_names');
            $heirBirthPlaces = $this->request->getPost('heir_birth_places');
            $heirBirthDates = $this->request->getPost('heir_birth_dates');
            $heirRelationships = $this->request->getPost('heir_relationships');
            $heirNiks = $this->request->getPost('heir_niks');
            $heirReporters = $this->request->getPost('heir_reporter');

            foreach ($heirNames as $index => $name) {
                $heirData[] = [
                    'name' => $name,
                    'birth_place' => $heirBirthPlaces[$index],
                    'birth_date' => $heirBirthDates[$index],
                    'relationship' => $heirRelationships[$index],
                    'nik' => $heirNiks[$index],
                    'is_reporter' => isset($heirReporters[$index]) ? 1 : 0
                ];
            }
            $kades = get_setting('struktur_organisasi','kepala_desa', false);
            $sigKades = get_setting('etc','ttd_kepala_desa', NULL);
            $camat = get_setting('struktur_organisasi','kepala_camat', false);
            $sigCamat = get_setting('etc','ttd_camat', NULL);
            // Save letter request
            $letterRequestId = $this->HeirRequestModel->insert([
                'resident_id' => $resident['id'],
                'letter_type_id' => $this->request->getPost('letter_type_id'),
                'name' => $this->request->getPost('name'),
                'pob' => $this->request->getPost('pob'),
                'dob' => $this->request->getPost('dob'),
                'religion' => $this->request->getPost('religion'),
                'occupation' => $this->request->getPost('occupation'),
                'address' => $this->request->getPost('address'),
                'date_of_death' => $this->request->getPost('date_of_death'),
                'time_of_death' => $this->request->getPost('time_of_death'),
                'place_of_death' => $this->request->getPost('place_of_death'),
                'cause_of_death' => $this->request->getPost('cause_of_death'),
                'burial_location' => $this->request->getPost('burial_location'),
                'burial_date' => $this->request->getPost('burial_date'),
                'heir_data' => json_encode($heirData),
                'status' => 'pending',
                'village_head_name' => $this->request->getPost('village_head_name'),
                'village_head_nip' => $this->request->getPost('village_head_nip'),
                'village_head_position' => $this->request->getPost('village_head_position'),
                'village_head_signature' => $sigKades,
                'subdistrict_head_name' => $this->request->getPost('subdistrict_head_name'),
                'subdistrict_head_nip' => $this->request->getPost('subdistrict_head_nip'),
                'subdistrict_head_position' => $this->request->getPost('subdistrict_head_position'),
                'subdistrict_head_signature' => $sigCamat,
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
                        $newName = $file->getRandomName();
                        $file->move(ROOTPATH . 'public/uploads/documents', $newName);

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
            if ($resident['user_id']) {
                $this->notificationModel->insert([
                    'user_id' => $resident['user_id'],
                    'title' => 'Pengajuan Surat Baru',
                    'message' => 'Pengajuan surat baru telah dibuat',
                    'type' => 'info',
                    'is_read' => 0
                ]);
            }

            $db->transComplete();
            return redirect()->to(base_url('heir-request/my-request'))->with('message', 'Pengajuan surat berhasil dibuat');
        } catch (\Exception $e) {
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
        $request = $this->HeirRequestModel->select('heir_certificates.*, residents.nik, letter_types.name as letter_type_name, letter_types.code as letter_type_code, letter_types.id as letter_type_id, letter_types.required_documents')
        ->join('letter_types', 'letter_types.id = heir_certificates.letter_type_id')
        ->join('residents', 'residents.id = heir_certificates.resident_id')->find($id);
        if (!$request) {
            return redirect()->to(base_url('heir-request'))->with('error', 'Pengajuan surat tidak ditemukan');
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
            'camat' => $this->settingModel->getByCategoryAndKey('struktur_organisasi', 'kepala_camat')
        ];

        return view('letter_requests/heir/edit', $data);
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
            $kades = get_setting('struktur_organisasi','kepala_desa', false);
            $sigKades = get_setting('etc','ttd_kepala_desa', NULL);
            $camat = get_setting('struktur_organisasi','kepala_camat', false);
            $sigCamat = get_setting('etc','ttd_camat', NULL);
            $this->HeirRequestModel->update($id, [
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
                'valid_from' => date('Y-m-d'),
                'village_head_name' => $this->request->getPost('village_head_name'),
                'village_head_nip' => $this->request->getPost('village_head_nip'),
                'village_head_position' => $this->request->getPost('village_head_position'),
                'village_head_signature' => $sigKades,
                'subdistrict_head_name' => $this->request->getPost('subdistrict_head_name'),
                'subdistrict_head_nip' => $this->request->getPost('subdistrict_head_nip'),
                'subdistrict_head_position' => $this->request->getPost('subdistrict_head_position'),
                'subdistrict_head_signature' => $sigCamat,
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

                        $existingAttachment = $this->attachmentModel->where('letter_request_id', $id)->where('name', $documentName)->where('letter_type_id', $this->request->getPost('letter_type_id'))->first();
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

            return redirect()->to(base_url('heir-request'))->with('message', 'Pengajuan surat berhasil diperbarui');
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
        $request = $this->HeirRequestModel->select('heir_certificates.*, residents.nik, letter_types.name as letter_type_name, letter_types.code as letter_type_code, letter_types.id as letter_type_id, letter_types.required_documents')
        ->join('letter_types', 'letter_types.id = heir_certificates.letter_type_id')
        ->join('residents', 'residents.id = heir_certificates.resident_id')
        ->where('residents.user_id', session()->get('user_id'))
        ->where('heir_certificates.id', $id)
        ->first();
        if (!$request) {
            return redirect()->to(base_url('heir-request/my-request'))->with('error', 'Pengajuan surat tidak ditemukan');
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
            'camat' => $this->settingModel->getByCategoryAndKey('struktur_organisasi', 'kepala_camat')
        ];

        return view('letter_requests/heir/edit_request', $data);
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
            $kades = get_setting('struktur_organisasi','kepala_desa', false);
            $sigKades = get_setting('etc','ttd_kepala_desa', NULL);
            $camat = get_setting('struktur_organisasi','kepala_camat', false);
            $sigCamat = get_setting('etc','ttd_camat', NULL);
            $this->HeirRequestModel->update($id, [
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
                'village_head_signature' => $sigKades,
                'subdistrict_head_name' => $this->request->getPost('subdistrict_head_name'),
                'subdistrict_head_nip' => $this->request->getPost('subdistrict_head_nip'),
                'subdistrict_head_position' => $this->request->getPost('subdistrict_head_position'),
                'subdistrict_head_signature' => $sigCamat,
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

                        $existingAttachment = $this->attachmentModel->where('letter_request_id', $id)->where('name', $documentName)->where('letter_type_id', $this->request->getPost('letter_type_id'))->first();
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

            return redirect()->to(base_url('heir-request/my-request'))->with('message', 'Pengajuan surat berhasil diperbarui');
        } catch (\Exception $e) {
            // Rollback transaction on error
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }


    
    public function delete($id)
    {

        // Get the letter request
        $request = $this->HeirRequestModel->join('residents', 'heir_certificates.resident_id = residents.id')->where('heir_certificates.id', $id);
        $url = 'heir-request';
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
        $this->HeirRequestModel->delete($id);

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