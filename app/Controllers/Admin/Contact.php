<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ContactModel;

class Contact extends BaseController
{
    protected $contactModel;

    public function __construct()
    {
        $this->contactModel = new ContactModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Contact List',
            'contacts' => $this->contactModel->findAll()
        ];

        return view('admin/contact/index', $data);
    }

    public function view($id)
    {
        $contact = $this->contactModel->find($id);
        
        if (!$contact) {
            return $this->response->setJSON(['success' => false, 'message' => 'Contact not found']);
        }

        // Update status to read
        $this->contactModel->update($id, ['status' => 'read']);

        return $this->response->setJSON([
            'success' => true,
            'data' => $contact
        ]);
    }
} 