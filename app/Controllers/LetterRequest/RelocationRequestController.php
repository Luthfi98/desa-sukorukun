<?php

namespace App\Controllers\LetterRequest;

use App\Controllers\BaseController;
use App\Models\RelocationRequestModel;
use App\Models\LetterTypeModel;
use App\Models\ResidentModel;
use App\Models\NotificationModel;
use App\Models\SettingModel;
use App\Models\DocumentAttachmentModel;

class RelocationRequestController extends BaseController
{
    protected $RelocationRequestModel;
    protected $letterTypeModel;
    protected $residentModel;
    protected $notificationModel;
    protected $settingModel;
    protected $attachmentModel;
    
    public function __construct()
    {
        $this->RelocationRequestModel = new RelocationRequestModel();
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
            'title' => 'Pengajuan Surat Pindah',
            'status' => $status,
            'requests' => $this->RelocationRequestModel->getByStatus($status),
            'letterTypes' => $this->letterTypeModel->where('template', 'sk_pindah')->getActive()
        ];
        // var_dump($data);die;
        
        return view('letter_requests/relocation/index', $data);
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
            'title' => 'Pengajuan Surat Pindah',
            'status' => $status,
            'requests' => $this->RelocationRequestModel->where('resident_id', $resident['id'])->orderBy('created_at', 'desc')->getByStatus($status),
            'letterTypes' => $this->letterTypeModel->where('template', 'sk_pindah')->getActive()
        ];
        
        return view('letter_requests/relocation/my_request', $data);
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
        $builder = $db->table('relocation_letters');
        $builder->select('relocation_letters.*, letter_types.name as letter_type_name, letter_types.code as letter_type_code, residents.name as resident_name, residents.nik as resident_nik, residents.user_id');
        $builder->join('letter_types', 'letter_types.id = relocation_letters.letter_type_id');
        $builder->join('residents', 'residents.id = relocation_letters.resident_id');
        $builder->where('relocation_letters.status', $status);
        $builder->where('relocation_letters.deleted_at', null);
        $url = 'relocation-request';
        if (session()->get('role') === 'resident') {
            $builder->where('residents.user_id', session()->get('user_id'));
            $url = 'relocation-request/my-request';
        }
        
        // Apply letter type filter
        if (!empty($letterTypeId)) {
            $builder->where('relocation_letters.letter_type_id', $letterTypeId);
        }
        
        // Apply date range filter
        if (!empty($dateStart)) {
            $builder->where('DATE(relocation_letters.created_at) >=', $dateStart);
        }
        if (!empty($dateEnd)) {
            $builder->where('DATE(relocation_letters.created_at) <=', $dateEnd);
        }
        
        // Get total records count
        $totalRecords = $builder->countAllResults(false);
        
        // Apply search filter if provided
        if (!empty($search)) {
            $builder->groupStart()
                ->like('residents.name', $search)
                ->orLike('residents.nik', $search)
                ->orLike('letter_types.name', $search)
                ->orLike('relocation_letters.number', $search)
                ->orLike('relocation_letters.reason', $search)
                ->groupEnd();
        }
        
        // Get filtered records count
        $filteredRecords = $builder->countAllResults(false);
        
        // Apply ordering
        $orderColumn = 'relocation_letters.created_at';
        $orderDir = 'DESC';
        
        if (!empty($order)) {
            $columns = [
                0 => 'relocation_letters.created_at',
                1 => 'residents.name',
                2 => 'residents.nik',
                3 => 'letter_types.name',
                4 => 'relocation_letters.number',
                5 => 'relocation_letters.reason'
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
                $row['reason'],
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
        $request = $this->RelocationRequestModel->select('relocation_letters.*, residents.nik, residents.gender, residents.occupation, letter_types.name as letter_type_name, users.name as processor_name')
            ->join('residents', 'relocation_letters.resident_id = residents.id')
            ->join('letter_types', 'relocation_letters.letter_type_id = letter_types.id', 'left')
            ->join('users', 'relocation_letters.processed_by = users.id', 'left');
        $url = 'relocation-request';
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
            'title' => 'Detail Surat Pindah',
            'request' => $request,
            'attachments' => $attachments,
            'url' => $url
        ];
        
        return view('letter_requests/relocation/show', $data);
    }

    public function process($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $request = $this->RelocationRequestModel->find($id);
        
        if (!$request) {
            return redirect()->to(base_url('relocation-request'))->with('error', 'Pengajuan surat tidak ditemukan');
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
        
        return view('letter_requests/relocation/process', $data);
    }

    public function updateStatus($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $request = $this->RelocationRequestModel->find($id);
        
        if (!$request) {
            return redirect()->to(base_url('relocation-request'))->with('error', 'Pengajuan surat tidak ditemukan');
        }
        
        $status = $this->request->getPost('status');
        $rejectionReason = $this->request->getPost('rejection_reason');
        
        // Process the request
        $this->RelocationRequestModel->processRequest($id, session()->get('user_id'), $status, $rejectionReason);
        
        // Generate reference number if approved
        if (($status === 'approved' || $status === 'completed') && empty($request['number'])) {
            $letterType = $this->letterTypeModel->find($request['letter_type_id']);
            $this->RelocationRequestModel->generateLetterNumber($id, $letterType['code']);
        }
        
        // Send notification to resident
        $resident = $this->residentModel->select('residents.*, users.email')->join('users', 'residents.user_id = users.id', 'left')->find($request['resident_id']);

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
                'link' => 'relocation-request/view/' . $id
            ]);
        }
        
        return redirect()->to(base_url('relocation-request'))->with('message', 'Status pengajuan surat berhasil diperbarui');
    }

    public function download($id)
    {
        $request = $this->RelocationRequestModel->select('relocation_letters.*, residents.nik, residents.gender, residents.occupation, relocation_letters.origin_address, residents.religion, residents.marital_status')
            ->join('residents', 'relocation_letters.resident_id = residents.id')
            ->where('relocation_letters.id', $id);
        $url = 'relocation-request';
        if (session()->get('role') === 'resident') {
            $request = $request->where('residents.user_id', session()->get('user_id'));
            $url .= '/my-request';
        }
        $request = $request->first();
        switch ($request['marital_status']) {
            case 'single':
                $request['marital_status'] = 'Belum Menikah';
                break;
            case 'married':
                $request['marital_status'] = 'Menikah';
                break;
            case 'divorced':
                $request['marital_status'] = 'Cerai';
                break;
            case 'widow':
                $request['marital_status'] = 'Cerai Mati';
                break;
        }
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
        
        // Render the letter template with data
        $html = view('letter_templates/' . $templateFile, [
            'request' => $request,
            'letterType' => $letterType
        ]);
        
        $dompdf->loadHtml($html);
        $dompdf->render();
        
        // Generate filename
        $filename = 'surat_' . strtolower(str_replace([' ', '/'], ['_', '_'], $letterType['name'])) . '_' . $request['nik'] . '.pdf';
        
        // Save the file to local
        
        if ($save) {
            $path = ROOTPATH . 'public/pdf/' . $filename;
            file_put_contents($path, $dompdf->output());
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
        $letterTypes = $this->letterTypeModel->where('template', 'sk_pindah')->getActive();
        
        $data = [
            'title' => 'Buat Surat Pindah',
            'letterTypes' => $letterTypes,
            'validation' => \Config\Services::validation(),
            'kades' => $this->settingModel->getByCategoryAndKey('struktur_organisasi', 'kepala_desa'),
            'camat' => $this->settingModel->getByCategoryAndKey('struktur_organisasi', 'kepala_camat'),
        ];
        
        return view('letter_requests/relocation/create', $data);
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
            'name' => 'required',
            'gender' => 'required|in_list[male,female]',
            'pob' => 'required',
            'dob' => 'required|valid_date',
            'nationality' => 'required',
            'occupation' => 'required',
            'education' => 'required',
            'origin_address' => 'required',
            'destination_dusun' => 'required',
            'destination_rt' => 'required',
            'destination_rw' => 'required',
            'destination_desa' => 'required',
            'destination_kecamatan' => 'required',
            'destination_kabupaten' => 'required',
            'destination_provinsi' => 'required',
            'reason' => 'required',
            // 'followers' => 'required',
            'move_date' => 'required|valid_date',
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
            // Prepare move followers data
            $moveFollowers = [];
            $foll_name = $this->request->getPost('foll_name');
            $foll_gender = $this->request->getPost('foll_gender');
            $foll_age = $this->request->getPost('foll_age');
            $foll_marital_status = $this->request->getPost('foll_marital_status');
            $foll_education = $this->request->getPost('foll_education');
            $foll_id_card = $this->request->getPost('foll_id_card');

            foreach ($foll_name as $index => $name) {
                $moveFollowers[] = [
                    'name' => $name,
                    'gender' => $foll_gender[$index],
                    'age' => $foll_age[$index],
                    'marital_status' => $foll_marital_status[$index],
                    'education' => $foll_education[$index],
                    'id_card' => $foll_id_card[$index]
                ];
            }
            // Prepare destination detail data
            $destinationDetail = [
                'dusun' => $this->request->getPost('destination_dusun'),
                'rt' => $this->request->getPost('destination_rt'),
                'rw' => $this->request->getPost('destination_rw'),
                'desa' => $this->request->getPost('destination_desa'),
                'kecamatan' => $this->request->getPost('destination_kecamatan'),
                'kabupaten' => $this->request->getPost('destination_kabupaten'),
                'provinsi' => $this->request->getPost('destination_provinsi')
            ];

            
            $kades = get_setting('struktur_organisasi','kepala_desa', false);
            $sigKades = get_setting('etc','ttd_kepala_desa', NULL);
            $camat = get_setting('struktur_organisasi','kepala_camat', false);
            $sigCamat = get_setting('etc','ttd_camat', NULL);
            
            // Save letter request
            $letterRequestId = $this->RelocationRequestModel->insert([
                'resident_id' => $resident['id'],
                'letter_type_id' => $this->request->getPost('letter_type_id'),
                'name' => $this->request->getPost('name'),
                'gender' => $this->request->getPost('gender'),
                'pob' => $this->request->getPost('pob'),
                'dob' => $this->request->getPost('dob'),
                'nationality' => $this->request->getPost('nationality'),
                'occupation' => $this->request->getPost('occupation'),
                'education' => $this->request->getPost('education'),
                'origin_address' => $this->request->getPost('origin_address'),
                'destination_detail' => json_encode($destinationDetail),
                'reason' => $this->request->getPost('reason'),
                'move_followers' => json_encode($moveFollowers),
                'move_date' => $this->request->getPost('move_date'),
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
                return redirect()->back()->withInput()->with('errors', $this->RelocationRequestModel->getErrors());
            }
            
            $letterType = $this->letterTypeModel->find($this->request->getPost('letter_type_id'));
            $this->RelocationRequestModel->generateLetterNumber($letterRequestId, $letterType['code']);
            
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

            if ($resident['user_id']) {
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
            return redirect()->to(base_url('relocation-request'))->with('message', 'Pengajuan surat berhasil dibuat');
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
        $letterTypes = $this->letterTypeModel->where('template', 'sk_pindah')->getActive();
        
        $data = [
            'title' => 'Pengajuan Pembuatan Surat Pindah',
            'letterTypes' => $letterTypes,
            'resident' => $this->residentModel->where('user_id', session()->get('user_id'))->first(),
            'validation' => \Config\Services::validation(),
            'kades' => $this->settingModel->getByCategoryAndKey('struktur_organisasi', 'kepala_desa'),
            'camat' => $this->settingModel->getByCategoryAndKey('struktur_organisasi', 'kepala_camat'),
        ];
        
        return view('letter_requests/relocation/create_request', $data);
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
            'name' => 'required',
            'gender' => 'required|in_list[male,female]',
            'pob' => 'required',
            'dob' => 'required|valid_date',
            'nationality' => 'required',
            'occupation' => 'required',
            'education' => 'required',
            'origin_address' => 'required',
            'destination_dusun' => 'required',
            'destination_rt' => 'required',
            'destination_rw' => 'required',
            'destination_desa' => 'required',
            'destination_kecamatan' => 'required',
            'destination_kabupaten' => 'required',
            'destination_provinsi' => 'required',
            'reason' => 'required',
            // 'followers' => 'required'
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
            // Prepare move followers data
            $moveFollowers = [];
            $foll_name = $this->request->getPost('foll_name');
            $foll_gender = $this->request->getPost('foll_gender');
            $foll_age = $this->request->getPost('foll_age');
            $foll_marital_status = $this->request->getPost('foll_marital_status');
            $foll_education = $this->request->getPost('foll_education');
            $foll_id_card = $this->request->getPost('foll_id_card');

            foreach ($foll_name as $index => $name) {
                $moveFollowers[] = [
                    'name' => $name,
                    'gender' => $foll_gender[$index],
                    'age' => $foll_age[$index],
                    'marital_status' => $foll_marital_status[$index],
                    'education' => $foll_education[$index],
                    'id_card' => $foll_id_card[$index]
                ];
            }
            // Prepare destination detail data
            $destinationDetail = [
                'dusun' => $this->request->getPost('destination_dusun'),
                'rt' => $this->request->getPost('destination_rt'),
                'rw' => $this->request->getPost('destination_rw'),
                'desa' => $this->request->getPost('destination_desa'),
                'kecamatan' => $this->request->getPost('destination_kecamatan'),
                'kabupaten' => $this->request->getPost('destination_kabupaten'),
                'provinsi' => $this->request->getPost('destination_provinsi')
            ];

            
            $kades = get_setting('struktur_organisasi','kepala_desa', false);
            $sigKades = get_setting('etc','ttd_kepala_desa', NULL);
            $camat = get_setting('struktur_organisasi','kepala_camat', false);
            $sigCamat = get_setting('etc','ttd_camat', NULL);
            
            // Save letter request
            $letterRequestId = $this->RelocationRequestModel->insert([
                'resident_id' => $resident['id'],
                'letter_type_id' => $this->request->getPost('letter_type_id'),
                'name' => $this->request->getPost('name'),
                'gender' => $this->request->getPost('gender'),
                'pob' => $this->request->getPost('pob'),
                'dob' => $this->request->getPost('dob'),
                'nationality' => $this->request->getPost('nationality'),
                'occupation' => $this->request->getPost('occupation'),
                'education' => $this->request->getPost('education'),
                'origin_address' => $this->request->getPost('origin_address'),
                'destination_detail' => json_encode($destinationDetail),
                'reason' => $this->request->getPost('reason'),
                'move_followers' => json_encode($moveFollowers),
                'move_date' => $this->request->getPost('move_date'),
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
            
            // var_dump($letterRequestId);die;
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
            return redirect()->to(base_url('relocation-request/my-request'))->with('message', 'Pengajuan surat berhasil dibuat');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Get the request data
        $request = $this->RelocationRequestModel->select('relocation_letters.*, residents.nik, letter_types.name as letter_type_name, letter_types.code as letter_type_code, letter_types.id as letter_type_id, letter_types.required_documents')
        ->join('letter_types', 'letter_types.id = relocation_letters.letter_type_id')
        ->join('residents', 'residents.id = relocation_letters.resident_id')->find($id);
        if (!$request) {
            return redirect()->to(base_url('relocation-request'))->with('error', 'Pengajuan surat tidak ditemukan');
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

        return view('letter_requests/relocation/edit', $data);
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
            'name' => 'required',
            'gender' => 'required|in_list[male,female]',
            'pob' => 'required',
            'dob' => 'required|valid_date',
            'nationality' => 'required',
            'occupation' => 'required',
            'education' => 'required',
            'origin_address' => 'required',
            'destination_dusun' => 'required',
            'destination_rt' => 'required',
            'destination_rw' => 'required',
            'destination_desa' => 'required',
            'destination_kecamatan' => 'required',
            'destination_kabupaten' => 'required',
            'destination_provinsi' => 'required',
            'reason' => 'required',
            // 'followers' => 'required',
            'move_date' => 'required|valid_date'
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
            // Prepare move followers data
            $moveFollowers = [];
            $foll_name = $this->request->getPost('foll_name');
            $foll_gender = $this->request->getPost('foll_gender');
            $foll_age = $this->request->getPost('foll_age');
            $foll_marital_status = $this->request->getPost('foll_marital_status');
            $foll_education = $this->request->getPost('foll_education');
            $foll_id_card = $this->request->getPost('foll_id_card');

            foreach ($foll_name as $index => $name) {
                $moveFollowers[] = [
                    'name' => $name,
                    'gender' => $foll_gender[$index],
                    'age' => $foll_age[$index],
                    'marital_status' => $foll_marital_status[$index],
                    'education' => $foll_education[$index],
                    'id_card' => $foll_id_card[$index]
                ];
            }
            
            // Prepare destination detail data
            $destinationDetail = [
                'dusun' => $this->request->getPost('destination_dusun'),
                'rt' => $this->request->getPost('destination_rt'),
                'rw' => $this->request->getPost('destination_rw'),
                'desa' => $this->request->getPost('destination_desa'),
                'kecamatan' => $this->request->getPost('destination_kecamatan'),
                'kabupaten' => $this->request->getPost('destination_kabupaten'),
                'provinsi' => $this->request->getPost('destination_provinsi')
            ];
            
            $kades = get_setting('struktur_organisasi','kepala_desa', false);
            $sigKades = get_setting('etc','ttd_kepala_desa', NULL);
            $camat = get_setting('struktur_organisasi','kepala_camat', false);
            $sigCamat = get_setting('etc','ttd_camat', NULL);
            
            // Update letter request
            $this->RelocationRequestModel->update($id, [
                'resident_id' => $resident['id'],
                'letter_type_id' => $this->request->getPost('letter_type_id'),
                'name' => $this->request->getPost('name'),
                'gender' => $this->request->getPost('gender'),
                'pob' => $this->request->getPost('pob'),
                'dob' => $this->request->getPost('dob'),
                'nationality' => $this->request->getPost('nationality'),
                'occupation' => $this->request->getPost('occupation'),
                'education' => $this->request->getPost('education'),
                'origin_address' => $this->request->getPost('origin_address'),
                'destination_detail' => json_encode($destinationDetail),
                'reason' => $this->request->getPost('reason'),
                'move_followers' => json_encode($moveFollowers),
                'move_date' => $this->request->getPost('move_date'),
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
            $documentIds = $this->request->getPost('document_ids') ?? [];
            
            // Upload new documents if any
            if (isset($documentFiles['documents']) && is_array($documentFiles['documents'])) {
                foreach ($documentFiles['documents'] as $index => $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $documentName = $documentNames[$index] ?? 'Document ' . ($index + 1);
                        $documentId = $documentIds[$index] ?? null;

                        $newName = $file->getRandomName();
                        $file->move(ROOTPATH . 'public/uploads/documents', $newName);

                        if ($documentId) {
                            // Update existing document
                            $this->attachmentModel->update($documentId, [
                                'name' => $documentName,
                                'file_path' => 'uploads/documents/' . $newName,
                                'file_type' => $file->getClientMimeType(),
                                'file_size' => $file->getSize(),
                            ]);
                        } else {
                            // Insert new document
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

            return redirect()->to(base_url('relocation-request'))->with('message', 'Pengajuan surat berhasil diperbarui');
        } catch (\Exception $e) {
            // Rollback transaction on error
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function editRequest($id)
    {
        // Check if user is resident
        if (session()->get('role') !== 'resident') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Get the request data
        $request = $this->RelocationRequestModel->select('relocation_letters.*, residents.nik, letter_types.name as letter_type_name, letter_types.code as letter_type_code, letter_types.id as letter_type_id, letter_types.required_documents')
        ->join('letter_types', 'letter_types.id = relocation_letters.letter_type_id')
        ->join('residents', 'residents.id = relocation_letters.resident_id')
        ->where('residents.user_id', session()->get('user_id'))
        ->where('relocation_letters.id', $id)
        ->first();
        if (!$request) {
            return redirect()->to(base_url('relocation-request/my-request'))->with('error', 'Pengajuan surat tidak ditemukan');
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

        return view('letter_requests/relocation/edit_request', $data);
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
            'name' => 'required',
            'gender' => 'required|in_list[male,female]',
            'pob' => 'required',
            'dob' => 'required|valid_date',
            'nationality' => 'required',
            'occupation' => 'required',
            'education' => 'required',
            'origin_address' => 'required',
            'destination_dusun' => 'required',
            'destination_rt' => 'required',
            'destination_rw' => 'required',
            'destination_desa' => 'required',
            'destination_kecamatan' => 'required',
            'destination_kabupaten' => 'required',
            'destination_provinsi' => 'required',
            'reason' => 'required',
            // 'followers' => 'required'
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
            // Prepare move followers data
            $moveFollowers = [];
            $foll_name = $this->request->getPost('foll_name');
            $foll_gender = $this->request->getPost('foll_gender');
            $foll_age = $this->request->getPost('foll_age');
            $foll_marital_status = $this->request->getPost('foll_marital_status');
            $foll_education = $this->request->getPost('foll_education');
            $foll_id_card = $this->request->getPost('foll_id_card');

            foreach ($foll_name as $index => $name) {
                $moveFollowers[] = [
                    'name' => $name,
                    'gender' => $foll_gender[$index],
                    'age' => $foll_age[$index],
                    'marital_status' => $foll_marital_status[$index],
                    'education' => $foll_education[$index],
                    'id_card' => $foll_id_card[$index]
                ];
            }
            
            // Prepare destination detail data
            $destinationDetail = [
                'dusun' => $this->request->getPost('destination_dusun'),
                'rt' => $this->request->getPost('destination_rt'),
                'rw' => $this->request->getPost('destination_rw'),
                'desa' => $this->request->getPost('destination_desa'),
                'kecamatan' => $this->request->getPost('destination_kecamatan'),
                'kabupaten' => $this->request->getPost('destination_kabupaten'),
                'provinsi' => $this->request->getPost('destination_provinsi')
            ];
            
            $kades = get_setting('struktur_organisasi','kepala_desa', false);
            $sigKades = get_setting('etc','ttd_kepala_desa', NULL);
            $camat = get_setting('struktur_organisasi','kepala_camat', false);
            $sigCamat = get_setting('etc','ttd_camat', NULL);
            
            // Update letter request
            $this->RelocationRequestModel->update($id, [
                'resident_id' => $resident['id'],
                'letter_type_id' => $this->request->getPost('letter_type_id'),
                'name' => $this->request->getPost('name'),
                'gender' => $this->request->getPost('gender'),
                'pob' => $this->request->getPost('pob'),
                'dob' => $this->request->getPost('dob'),
                'nationality' => $this->request->getPost('nationality'),
                'occupation' => $this->request->getPost('occupation'),
                'education' => $this->request->getPost('education'),
                'origin_address' => $this->request->getPost('origin_address'),
                'destination_detail' => json_encode($destinationDetail),
                'reason' => $this->request->getPost('reason'),
                'move_followers' => json_encode($moveFollowers),
                'move_date' => $this->request->getPost('move_date'),
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

            return redirect()->to(base_url('relocation-request/my-request'))->with('message', 'Pengajuan surat berhasil diperbarui');
        } catch (\Exception $e) {
            // Rollback transaction on error
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }


    
    public function delete($id)
    {
        // Get the letter request
        $request = $this->RelocationRequestModel->join('residents', 'relocation_letters.resident_id = residents.id')->where('relocation_letters.id', $id);
        $url = 'relocation-request';
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
        $this->RelocationRequestModel->delete($id);

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