<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ResidentModel;

class Profile extends BaseController
{
    protected $userModel;
    protected $residentModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->residentModel = new ResidentModel();
    }
    
    public function index()
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('auth/login'))->with('error', 'Silakan login terlebih dahulu');
        }
        
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to(base_url('auth/logout'))->with('error', 'Akun tidak ditemukan');
        }
        
        // For resident users, get resident data
        $resident = null;
        if (session()->get('role') === 'resident') {
            $resident = $this->residentModel->where('user_id', $userId)->first();
        }
        
        $data = [
            'title' => 'Profil Saya',
            'user' => $user,
            'resident' => $resident
        ];
        
        return view('profile/index', $data);
    }
    
    public function update()
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('auth/login'))->with('error', 'Silakan login terlebih dahulu');
        }
        
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to(base_url('auth/logout'))->with('error', 'Akun tidak ditemukan');
        }
        
        $rules = [
            'name' => 'required|min_length[3]|max_length[50]',
            'email' => 'required|valid_email'
        ];
        
        // If email changed, check for uniqueness
        if ($user['email'] !== $this->request->getPost('email')) {
            $rules['email'] .= '|is_unique[users.email]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $this->userModel->update($userId, [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email')
        ]);
        
        // Update session data
        session()->set([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email')
        ]);
        
        return redirect()->to(base_url('profile'))->with('message', 'Profil berhasil diperbarui');
    }
    
    public function updateResidentData()
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('auth/login'))->with('error', 'Silakan login terlebih dahulu');
        }
        
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to(base_url('auth/logout'))->with('error', 'Akun tidak ditemukan');
        }
        
        // Check if resident exists
        $resident = $this->residentModel->where('user_id', $userId)->first();
        
        // Set validation rules for resident data
        $rules = [
            'nik' => 'required|numeric|exact_length[16]',
            'kk' => 'required|numeric|exact_length[16]',
            'name' => 'required|alpha_numeric_space|min_length[3]|max_length[100]',
            'birth_place' => 'required|alpha_numeric_space|max_length[100]',
            'birth_date' => 'required|valid_date',
            'gender' => 'required|in_list[male,female]',
            'address' => 'required',
            'rt' => 'required|max_length[5]',
            'rw' => 'required|max_length[5]',
            'village' => 'required|max_length[100]',
            'district' => 'required|max_length[100]',
            'religion' => 'required|max_length[20]',
            'marital_status' => 'required|in_list[single,married,divorced,widowed]',
            'occupation' => 'required|max_length[100]',
            'nationality' => 'required|max_length[50]',
            'education' => 'required|max_length[50]',
        ];
        
        // If resident exists and NIK changed, check for uniqueness
        if ($resident && $resident['nik'] !== $this->request->getPost('nik')) {
            $rules['nik'] .= '|is_unique[residents.nik,id,' . $resident['id'] . ']';
        } elseif (!$resident) {
            // If creating new resident
            $rules['nik'] .= '|is_unique[residents.nik]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $residentData = [
            'nik' => $this->request->getPost('nik'),
            'kk' => $this->request->getPost('kk'),
            'name' => $this->request->getPost('name'),
            'birth_place' => $this->request->getPost('birth_place'),
            'birth_date' => $this->request->getPost('birth_date'),
            'gender' => $this->request->getPost('gender'),
            'address' => $this->request->getPost('address'),
            'rt' => $this->request->getPost('rt'),
            'rw' => $this->request->getPost('rw'),
            'village' => $this->request->getPost('village'),
            'district' => $this->request->getPost('district'),
            'religion' => $this->request->getPost('religion'),
            'marital_status' => $this->request->getPost('marital_status'),
            'occupation' => $this->request->getPost('occupation'),
            'nationality' => $this->request->getPost('nationality'),
            'education' => $this->request->getPost('education'),
            'father_name' => $this->request->getPost('father_name'),
            'mother_name' => $this->request->getPost('mother_name'),
            'user_id' => $userId
        ];
        if ($resident) {
            // Update existing resident data
            $this->residentModel->update($resident['id'], $residentData);
            // var_dump($this->residentModel->update($resident['id'], $residentData));die;

        } else {
            // Create new resident data
            $this->residentModel->insert($residentData);
        }
        // var_dump($residentData);die;

        
        // Update user name in users table to match resident name
        $this->userModel->update($userId, [
            'name' => $this->request->getPost('name')
        ]);
        
        // Update session data
        session()->set([
            'name' => $this->request->getPost('name')
        ]);
        
        return redirect()->to(base_url('profile'))->with('message', 'Data diri berhasil diperbarui');
    }
    
    public function changePassword()
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('auth/login'))->with('error', 'Silakan login terlebih dahulu');
        }
        
        $data = [
            'title' => 'Ubah Password'
        ];
        
        return view('profile/change_password', $data);
    }
    
    public function updatePassword()
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('auth/login'))->with('error', 'Silakan login terlebih dahulu');
        }
        
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to(base_url('auth/logout'))->with('error', 'Akun tidak ditemukan');
        }
        
        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        
        // Verify current password
        if (!password_verify($currentPassword, $user['password_hash'])) {
            return redirect()->back()->with('error', 'Password saat ini tidak valid');
        }
        
        // Update password
        $this->userModel->update($userId, [
            'password' => $newPassword
        ]);
        
        return redirect()->to(base_url('profile'))->with('message', 'Password berhasil diperbarui');
    }
} 