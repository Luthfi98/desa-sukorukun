<?php

namespace App\Controllers;

use App\Models\LetterRequestModel;
use App\Models\LetterTypeModel;
use App\Models\ResidentModel;
use App\Models\NotificationModel;

class PengajuanSurat extends BaseController
{
    protected $letterRequestModel;
    protected $letterTypeModel;
    protected $residentModel;
    protected $notificationModel;
    
    public function __construct()
    {
        $this->letterRequestModel = new LetterRequestModel();
        $this->letterTypeModel = new LetterTypeModel();
        $this->residentModel = new ResidentModel();
        $this->notificationModel = new NotificationModel();
    }
    
    public function index()
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $status = $this->request->getGet('status') ?? 'pending';
        
        $data = [
            'title' => 'Pengajuan Surat',
            'status' => $status,
            'requests' => $this->letterRequestModel->getByStatus($status),
            'letterTypes' => $this->letterTypeModel->getActive()
        ];
        
        return view('pengajuan_surat/index', $data);
    }
    
    public function view($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $request = $this->letterRequestModel->find($id);
        
        if (!$request) {
            return redirect()->to(base_url('pengajuan-surat'))->with('error', 'Pengajuan surat tidak ditemukan');
        }
        
        $letterType = $this->letterTypeModel->find($request['letter_type_id']);
        $resident = $this->residentModel->find($request['resident_id']);
        
        // Get document attachments
        $documentAttachmentModel = new \App\Models\DocumentAttachmentModel();
        $attachments = $documentAttachmentModel->where('letter_request_id', $id)->findAll();
        
        $data = [
            'title' => 'Detail Pengajuan Surat',
            'request' => $request,
            'letterType' => $letterType,
            'resident' => $resident,
            'attachments' => $attachments
        ];
        
        return view('pengajuan_surat/view', $data);
    }
    
    public function process($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $request = $this->letterRequestModel->find($id);
        
        if (!$request) {
            return redirect()->to(base_url('pengajuan-surat'))->with('error', 'Pengajuan surat tidak ditemukan');
        }
        
        $letterType = $this->letterTypeModel->find($request['letter_type_id']);
        $resident = $this->residentModel->find($request['resident_id']);
        
        // Get document attachments
        $documentAttachmentModel = new \App\Models\DocumentAttachmentModel();
        $attachments = $documentAttachmentModel->getByLetterRequestId($id);
        
        $data = [
            'title' => 'Proses Pengajuan Surat',
            'request' => $request,
            'letterType' => $letterType,
            'resident' => $resident,
            'attachments' => $attachments
        ];
        
        return view('pengajuan_surat/process', $data);
    }
    
    public function updateStatus($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $request = $this->letterRequestModel->find($id);
        
        if (!$request) {
            return redirect()->to(base_url('pengajuan-surat'))->with('error', 'Pengajuan surat tidak ditemukan');
        }
        
        $status = $this->request->getPost('status');
        $rejectionReason = $this->request->getPost('rejection_reason');
        
        // Process the request
        $this->letterRequestModel->processRequest($id, session()->get('user_id'), $status, $rejectionReason);
        
        // Generate reference number if approved
        if ($status === 'approved') {
            $letterType = $this->letterTypeModel->find($request['letter_type_id']);
            $this->letterRequestModel->generateReferenceNumber($id, $letterType['code']);
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
                'link' => 'surat-pengantar/view/' . $id
            ]);
        }
        
        return redirect()->to(base_url('pengajuan-surat'))->with('message', 'Status pengajuan surat berhasil diperbarui');
    }
    
    /**
     * Process a letter request
     */
    public function download($id)
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        $request = $this->letterRequestModel->find($id);
        
        if (!$request) {
            return redirect()->to(base_url('pengajuan-surat'))->with('error', 'Pengajuan surat tidak ditemukan');
        }
        
        $letterType = $this->letterTypeModel->find($request['letter_type_id']);
        $resident = $this->residentModel->find($request['resident_id']);
        
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
            'resident' => $resident,
            'letterType' => $letterType
        ]);
        
        $dompdf->loadHtml($html);
        $dompdf->render();
        
        // Generate filename
        $filename = 'surat_' . strtolower(str_replace(' ', '_', $letterType['name'])) . '_' . $resident['nik'] . '.pdf';
        
        // Stream the file
        return $dompdf->stream($filename, ['Attachment' => true]);
    }
    
    /**
     * Display the user's own letter requests
     */
    public function myRequests()
    {
        // Check if user is logged in as resident
        if (session()->get('role') !== 'resident') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Get resident data for the logged-in user
        $resident = $this->residentModel->where('user_id', session()->get('user_id'))->first();
        
        if (!$resident) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Data penduduk tidak ditemukan. Silakan hubungi administrator.');
        }
        
        $data = [
            'title' => 'My Letter Requests',
            'resident' => $resident
        ];
        
        return view('pengajuan_surat/my_requests', $data);
    }
    
    /**
     * Get DataTables data for my requests
     */
    public function getMyRequestsDataTable()
    {
        // Check if user is logged in as resident
        if (session()->get('role') !== 'resident') {
            return $this->response->setJSON([
                'draw' => 1,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
        
        // Get resident data for the logged-in user
        $resident = $this->residentModel->where('user_id', session()->get('user_id'))->first();
        
        if (!$resident) {
            return $this->response->setJSON([
                'draw' => 1,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
        
        $draw = intval($this->request->getGet('draw'));
        $start = intval($this->request->getGet('start'));
        $length = intval($this->request->getGet('length'));
        $search = $this->request->getGet('search')['value'] ?? '';
        $order = $this->request->getGet('order');
        
        // Get total records count
        $totalRecords = $this->letterRequestModel->where('resident_id', $resident['id'])->countAllResults();
        
        // Create a query builder for the main query
        $db = \Config\Database::connect();
        $builder = $db->table('letter_requests');
        $builder->select('letter_requests.*, letter_types.name as letter_type_name');
        $builder->join('letter_types', 'letter_types.id = letter_requests.letter_type_id');
        $builder->where('letter_requests.resident_id', $resident['id']);
        
        // Apply search filter if provided
        $filteredRecords = $totalRecords;
        if (!empty($search)) {
            $builder->groupStart()
                ->like('letter_types.name', $search)
                ->orLike('letter_requests.number', $search)
                ->orLike('letter_requests.purpose', $search)
                ->groupEnd();
            $filteredRecords = $builder->countAllResults(false);
        }
        
        // Apply ordering
        $orderColumn = 'letter_requests.created_at';
        $orderDir = 'DESC';
        
        if (!empty($order)) {
            $columns = [
                0 => 'letter_types.name',
                1 => 'letter_requests.created_at',
                2 => 'letter_requests.status',
                3 => 'letter_requests.number'
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
        
        // Return the data directly without formatting
        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }
    
    /**
     * Display form to create a new letter request
     */
    public function new()
    {
        // Check if user is logged in as resident
        if (session()->get('role') !== 'resident') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Get resident data for the logged-in user
        $resident = $this->residentModel->where('user_id', session()->get('user_id'))->first();
        
        if (!$resident) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Data penduduk tidak ditemukan. Silakan hubungi administrator.');
        }
        
        // Get active letter types
        $letterTypes = $this->letterTypeModel->getActive();
        
        $data = [
            'title' => 'Create New Letter Request',
            'resident' => $resident,
            'letterTypes' => $letterTypes,
            'validation' => \Config\Services::validation()
        ];
        
        return view('pengajuan_surat/create', $data);
    }
    
    /**
     * Process new letter request creation
     */
    public function create()
    {
        // Check if user is logged in as resident
        if (session()->get('role') !== 'resident') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Validate input
        $rules = [
            'letter_type_id' => 'required|numeric',
            'purpose' => 'required|min_length[10]',
            'description' => 'permit_empty',
            'documents.*' => 'uploaded[documents]|max_size[documents,2048]|mime_in[documents,application/pdf,image/jpeg,image/png]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Get resident data for the logged-in user
        $resident = $this->residentModel->where('user_id', session()->get('user_id'))->first();
        
        if (!$resident) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Data penduduk tidak ditemukan. Silakan hubungi administrator.');
        }
        
        // Check if 'status' is a valid field in our model
        $allowedFields = $this->letterRequestModel->allowedFields;
        
        // Create request data
        $requestData = [
            'letter_type_id' => $this->request->getPost('letter_type_id'),
            'resident_id' => $resident['id'],
            'purpose' => $this->request->getPost('purpose'),
            'description' => $this->request->getPost('description')
        ];
        
        // Add status field only if it's in the allowed fields
        if (in_array('status', $allowedFields)) {
            $requestData['status'] = 'pending';
        }
        
        // Save to database
        $this->letterRequestModel->insert($requestData);
        $letterRequestId = $this->letterRequestModel->getInsertID();
        
        // Handle file uploads
        $files = $this->request->getFiles();
        $documentNames = $this->request->getPost('document_names');
        
        if (isset($files['documents']) && is_array($files['documents'])) {
            $documentAttachmentModel = new \App\Models\DocumentAttachmentModel();
            foreach ($files['documents'] as $index => $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $documentName = $documentNames[$index] ?? 'Document ' . ($index + 1);
                    
                    // Generate unique filename
                    $newName = $file->getRandomName();
                    
                    // Move file to uploads directory
                    $file->move(WRITEPATH . 'uploads/documents', $newName);
                    
                    // Save to database
                    $documentAttachmentModel->insert([
                        'letter_request_id' => $letterRequestId,
                        'name' => $documentName,
                        'file_path' => 'uploads/documents/' . $newName,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'description' => 'Dokumen ' . $documentName . ' untuk pengajuan surat'
                    ]);
                }
            }
        }
        
        // Get the letter type name for notification
        $letterType = $this->letterTypeModel->find($requestData['letter_type_id']);
        $letterTypeName = $letterType ? $letterType['name'] : 'Unknown';
        
        // Send notification to admin
        $this->notificationModel->insert([
            'user_id' => 1, // Assuming admin has user_id = 1
            'title' => 'New Letter Request',
            'message' => 'A new letter request (' . $letterTypeName . ') has been submitted by ' . $resident['name'],
            'link' => 'pengajuan-surat'
        ]);
        
        return redirect()->to(base_url('letter-requests/my-requests'))->with('message', 'Letter request submitted successfully');
    }
    
    /**
     * Display form to edit an existing letter request
     */
    public function edit($id)
    {
        // Check if user is logged in as resident
        if (session()->get('role') !== 'resident') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Get resident data for the logged-in user
        $resident = $this->residentModel->where('user_id', session()->get('user_id'))->first();
        
        if (!$resident) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Data penduduk tidak ditemukan. Silakan hubungi administrator.');
        }
        
        // Get the letter request
        $request = $this->letterRequestModel->find($id);
        
        // Check if request exists and belongs to the user
        if (!$request || $request['resident_id'] != $resident['id']) {
            return redirect()->to(base_url('letter-requests/my-requests'))->with('error', 'Letter request not found or you do not have permission to edit it');
        }
        
        // Check if request can be edited (only pending requests can be edited)
        if (isset($request['status']) && $request['status'] != 'pending') {
            return redirect()->to(base_url('letter-requests/my-requests'))->with('error', 'This request cannot be edited because it is already being processed');
        }
        
        // Get active letter types
        $letterTypes = $this->letterTypeModel->getActive();
        $letterType = $this->letterTypeModel->find($request['letter_type_id']);
        
        // Get document attachments
        $documentAttachmentModel = new \App\Models\DocumentAttachmentModel();
        $attachments = $documentAttachmentModel->where('letter_request_id', $id)->findAll();
        
        $data = [
            'title' => 'Edit Letter Request',
            'request' => $request,
            'letterType' => $letterType,
            'letterTypes' => $letterTypes,
            'resident' => $resident,
            'validation' => \Config\Services::validation(),
            'attachments' => $attachments
        ];
        
        return view('pengajuan_surat/edit', $data);
    }
    
    /**
     * Process letter request update
     */
    public function update($id)
    {
        // Check if user is logged in as resident
        if (session()->get('role') !== 'resident') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Validate input
        $rules = [
            'letter_type_id' => 'required|numeric',
            'purpose' => 'required|min_length[10]',
            'description' => 'permit_empty',
            'documents.*' => 'uploaded[documents]|max_size[documents,2048]|mime_in[documents,application/pdf,image/jpeg,image/png]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Get resident data for the logged-in user
        $resident = $this->residentModel->where('user_id', session()->get('user_id'))->first();
        
        if (!$resident) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Data penduduk tidak ditemukan. Silakan hubungi administrator.');
        }
        
        // Get the letter request
        $request = $this->letterRequestModel->find($id);
        
        // Check if request exists and belongs to the user
        if (!$request || $request['resident_id'] != $resident['id']) {
            return redirect()->to(base_url('letter-requests/my-requests'))->with('error', 'Letter request not found or you do not have permission to edit it');
        }
        
        // Check if request can be edited (only pending requests can be edited)
        if (isset($request['status']) && $request['status'] != 'pending') {
            return redirect()->to(base_url('letter-requests/my-requests'))->with('error', 'This request cannot be edited because it is already being processed');
        }
        
        // Update request data
        $updateData = [
            'letter_type_id' => $this->request->getPost('letter_type_id'),
            'purpose' => $this->request->getPost('purpose'),
            'description' => $this->request->getPost('description')
        ];
        
        // Save to database
        $this->letterRequestModel->update($id, $updateData);
        
        // Handle file uploads
        $files = $this->request->getFiles();
        $documentNames = $this->request->getPost('document_names');
        
        if (isset($files['documents']) && is_array($files['documents'])) {
            $documentAttachmentModel = new \App\Models\DocumentAttachmentModel();
            
            // Delete existing attachments if any
            $existingAttachments = $documentAttachmentModel->where('letter_request_id', $id)->findAll();
            foreach ($existingAttachments as $attachment) {
                // Delete file from storage
                if (file_exists(WRITEPATH . $attachment['file_path'])) {
                    unlink(WRITEPATH . $attachment['file_path']);
                }
                // Delete from database
                $documentAttachmentModel->delete($attachment['id']);
            }
            
            // Upload new files
            foreach ($files['documents'] as $index => $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $documentName = $documentNames[$index] ?? 'Document ' . ($index + 1);
                    
                    // Generate unique filename
                    $newName = $file->getRandomName();
                    
                    // Move file to uploads directory
                    $file->move(WRITEPATH . 'uploads/documents', $newName);
                    
                    // Save to database
                    $documentAttachmentModel->insert([
                        'letter_request_id' => $id,
                        'name' => $documentName,
                        'file_path' => 'uploads/documents/' . $newName,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'description' => 'Dokumen ' . $documentName . ' untuk pengajuan surat'
                    ]);   
                }
            }
        }
        
        return redirect()->to(base_url('letter-requests/my-requests'))->with('message', 'Letter request updated successfully');
    }
    
    /**
     * Delete a letter request
     */
    public function delete($id)
    {
        // Check if user is logged in as resident
        if (session()->get('role') !== 'resident') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Get resident data for the logged-in user
        $resident = $this->residentModel->where('user_id', session()->get('user_id'))->first();
        
        if (!$resident) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Data penduduk tidak ditemukan. Silakan hubungi administrator.');
        }
        
        // Get the letter request
        $request = $this->letterRequestModel->find($id);
        
        // Check if request exists and belongs to the user
        if (!$request || $request['resident_id'] != $resident['id']) {
            return redirect()->to(base_url('letter-requests/my-requests'))->with('error', 'Letter request not found or you do not have permission to delete it');
        }
        
        // Check if request can be deleted (only pending requests can be deleted)
        if (isset($request['status']) && $request['status'] != 'pending') {
            return redirect()->to(base_url('letter-requests/my-requests'))->with('error', 'This request cannot be deleted because it is already being processed');
        }
        
        // Delete from database (soft delete)
        $this->letterRequestModel->delete($id);
        
        return redirect()->to(base_url('letter-requests/my-requests'))->with('message', 'Letter request deleted successfully');
    }
    
    /**
     * Display detail of a letter request for resident
     */
    public function viewDetail($id)
    {
        // Check if user is logged in as resident
        if (session()->get('role') !== 'resident') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Get resident data for the logged-in user
        $resident = $this->residentModel->where('user_id', session()->get('user_id'))->first();
        
        if (!$resident) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Data penduduk tidak ditemukan. Silakan hubungi administrator.');
        }
        
        // Get the letter request
        $request = $this->letterRequestModel->find($id);
        
        // Check if request exists and belongs to the user
        if (!$request || $request['resident_id'] != $resident['id']) {
            return redirect()->to(base_url('letter-requests/my-requests'))->with('error', 'Pengajuan surat tidak ditemukan atau Anda tidak memiliki akses');
        }
        
        // Get letter type information
        $letterType = $this->letterTypeModel->find($request['letter_type_id']);
        
        // Get processor information if request was processed
        $processor = null;
        if (!empty($request['processed_by'])) {
            $userModel = new \App\Models\UserModel();
            $processor = $userModel->find($request['processed_by']);
        }
        
        // Get document attachments
        $documentAttachmentModel = new \App\Models\DocumentAttachmentModel();
        $attachments = $documentAttachmentModel->getByLetterRequestId($id);
        
        $data = [
            'title' => 'Detail Pengajuan Surat',
            'request' => $request,
            'letterType' => $letterType,
            'resident' => $resident,
            'processor' => $processor,
            'attachments' => $attachments
        ];
        
        return view('pengajuan_surat/view_detail', $data);
    }
    
    /**
     * Download letter as PDF for resident
     */
    public function downloadPdf($id)
    {
        // Check if user is logged in as resident
        if (session()->get('role') !== 'resident') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Get resident data for the logged-in user
        $resident = $this->residentModel->where('user_id', session()->get('user_id'))->first();
        
        if (!$resident) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Data penduduk tidak ditemukan. Silakan hubungi administrator.');
        }
        
        // Get the letter request
        $request = $this->letterRequestModel->find($id);
        
        // Check if request exists and belongs to the user
        if (!$request || $request['resident_id'] != $resident['id']) {
            return redirect()->to(base_url('letter-requests/my-requests'))->with('error', 'Pengajuan surat tidak ditemukan atau Anda tidak memiliki akses');
        }
        
        // Check if the letter is completed or approved
        if (!isset($request['status']) || ($request['status'] != 'completed' && $request['status'] != 'approved')) {
            return redirect()->to(base_url('letter-requests/my-requests'))->with('error', 'Surat belum disetujui atau selesai diproses');
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
        var_dump($templateFile);die;
        // Render the letter template with data
        $html = view('letter_templates/' . $templateFile, [
            'request' => $request,
            'resident' => $resident,
            'letterType' => $letterType
        ]);
        
        $dompdf->loadHtml($html);
        $dompdf->render();
        
        // Generate filename
        $filename = 'surat_' . strtolower(str_replace(' ', '_', $letterType['name'])) . '_' . $resident['nik'] . '.pdf';
        
        // Stream the file
        return $dompdf->stream($filename, ['Attachment' => true]);
    }
    
    public function getDataTable()
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return $this->response->setJSON([
                'draw' => 1,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
        
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
        $builder = $db->table('letter_requests');
        $builder->select('letter_requests.*, letter_types.name as letter_type_name, letter_types.code as letter_type_code, residents.name as resident_name, residents.nik as resident_nik');
        $builder->join('letter_types', 'letter_types.id = letter_requests.letter_type_id');
        $builder->join('residents', 'residents.id = letter_requests.resident_id');
        $builder->where('letter_requests.status', $status);
        
        // Apply letter type filter
        if (!empty($letterTypeId)) {
            $builder->where('letter_requests.letter_type_id', $letterTypeId);
        }
        
        // Apply date range filter
        if (!empty($dateStart)) {
            $builder->where('DATE(letter_requests.created_at) >=', $dateStart);
        }
        if (!empty($dateEnd)) {
            $builder->where('DATE(letter_requests.created_at) <=', $dateEnd);
        }
        
        // Get total records count
        $totalRecords = $builder->countAllResults(false);
        
        // Apply search filter if provided
        if (!empty($search)) {
            $builder->groupStart()
                ->like('residents.name', $search)
                ->orLike('residents.nik', $search)
                ->orLike('letter_types.name', $search)
                ->orLike('letter_requests.number', $search)
                ->orLike('letter_requests.purpose', $search)
                ->groupEnd();
        }
        
        // Get filtered records count
        $filteredRecords = $builder->countAllResults(false);
        
        // Apply ordering
        $orderColumn = 'letter_requests.created_at';
        $orderDir = 'DESC';
        
        if (!empty($order)) {
            $columns = [
                0 => 'letter_requests.created_at',
                1 => 'residents.name',
                2 => 'residents.nik',
                3 => 'letter_types.name',
                4 => 'letter_requests.number',
                5 => 'letter_requests.purpose'
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
            $actions .= '<a href="' . base_url('pengajuan-surat/view/' . $row['id']) . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
            
            if ($status === 'pending' || $status === 'processing') {
                $actions .= '<a href="' . base_url('pengajuan-surat/process/' . $row['id']) . '" class="btn btn-sm btn-warning"><i class="fas fa-cog"></i></a>';
            }
            
            if ($status === 'approved' || $status === 'completed') {
                $actions .= '<a href="' . base_url('pengajuan-surat/download/' . $row['id']) . '" class="btn btn-sm btn-success" target="_blank"><i class="fas fa-download"></i></a>';
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
    
    /**
     * Display form to create a letter request for a resident (admin/staff only)
     */
    public function createForResident()
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Get active letter types
        $letterTypes = $this->letterTypeModel->getActive();
        
        $data = [
            'title' => 'Buat Pengajuan Surat untuk Penduduk',
            'letterTypes' => $letterTypes,
            'validation' => \Config\Services::validation()
        ];
        
        return view('pengajuan_surat/create_for_resident', $data);
    }
    
    /**
     * Process creating a letter request for a resident (admin/staff only)
     */
    public function processCreateForResident()
    {
        // Check if user is admin or staff
        if (session()->get('role') !== 'admin' && session()->get('role') !== 'staff') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Validate input
        $rules = [
            'nik' => 'required|numeric|min_length[16]|max_length[16]',
            'letter_type_id' => 'required|numeric',
            'purpose' => 'required|min_length[10]',
            'description' => 'permit_empty',
            'documents.*' => 'uploaded[documents]|max_size[documents,2048]|mime_in[documents,application/pdf,image/jpeg,image/png]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Get resident by NIK
        $resident = $this->residentModel->where('nik', $this->request->getPost('nik'))->first();
        
        if (!$resident) {
            return redirect()->back()->withInput()->with('error', 'Penduduk dengan NIK tersebut tidak ditemukan');
        }
        
        // Create request data
        $requestData = [
            'letter_type_id' => $this->request->getPost('letter_type_id'),
            'resident_id' => $resident['id'],
            'purpose' => $this->request->getPost('purpose'),
            'description' => $this->request->getPost('description'),
            'status' => 'approved', // Automatically approved
            'processed_by' => session()->get('user_id'),
            'processed_at' => date('Y-m-d H:i:s')
        ];
        
        // Save to database
        $this->letterRequestModel->insert($requestData);
        $letterRequestId = $this->letterRequestModel->getInsertID();
        
        // Handle file uploads
        $files = $this->request->getFiles();
        $documentNames = $this->request->getPost('document_names');
        
        if (isset($files['documents']) && is_array($files['documents'])) {
            $documentAttachmentModel = new \App\Models\DocumentAttachmentModel();
            
            foreach ($files['documents'] as $index => $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $documentName = $documentNames[$index] ?? 'Document ' . ($index + 1);
                    
                    // Generate unique filename
                    $newName = $file->getRandomName();
                    
                    // Move file to uploads directory
                    $file->move(WRITEPATH . 'uploads/documents', $newName);
                    
                    // Save to database
                    $documentAttachmentModel->insert([
                        'letter_request_id' => $letterRequestId,
                        'name' => $documentName,
                        'file_path' => 'uploads/documents/' . $newName,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'description' => 'Dokumen ' . $documentName . ' untuk pengajuan surat'
                    ]);
                }
            }
        }
        
        // Generate reference number
        $letterType = $this->letterTypeModel->find($requestData['letter_type_id']);
        $this->letterRequestModel->generateReferenceNumber($letterRequestId, $letterType['code']);
        
        // Send notification to resident if they have a user account
        if (!empty($resident['user_id'])) {
            $this->notificationModel->insert([
                'user_id' => $resident['user_id'],
                'title' => 'Pengajuan Surat ' . $letterType['name'],
                'message' => 'Pengajuan surat ' . $letterType['name'] . ' Anda telah dibuat dan disetujui oleh admin.',
                'link' => 'letter-requests/view-detail/' . $letterRequestId
            ]);
        }
        
        return redirect()->to(base_url('pengajuan-surat'))->with('message', 'Pengajuan surat berhasil dibuat dan disetujui');
    }
} 