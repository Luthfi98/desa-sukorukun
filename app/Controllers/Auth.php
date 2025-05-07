<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ResidentModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $residentModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->residentModel = new ResidentModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        return redirect()->to('/auth/login');
    }

    public function login()
    {
        if ($this->session->has('user_id')) {
            return redirect()->to('/dashboard');
        }
        
        return view('auth/login');
    }
    
    public function adminLogin()
    {
        if ($this->session->has('user_id')) {
            $role = $this->session->get('role');
            
            if ($role === 'admin') {
                return redirect()->to('/dashboard');
            } else {
                $this->session->destroy();
            }
        }
        
        return view('auth/admin_login');
    }

    public function authenticate()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $isAdminLogin = $this->request->getPost('admin_login') ?? false;
        
        $user = $this->userModel->findByUsername($username);
        
        // Check if user exists and password is correct
        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            return redirect()->back()->with('error', 'Username atau password salah!')->withInput();
        }
        
        // Check if user is active
        if ($user['status'] !== 'active') {
            return redirect()->back()->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.')->withInput();
        }
        
        // If it's an admin login, check if user is an admin
        if ($isAdminLogin && $user['role'] !== 'admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses sebagai administrator.')->withInput();
        }
        
        // Set session data
        $this->session->set([
            'user_id' => $user['id'],
            'username' => $user['username'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'is_logged_in' => true
        ]);
        
        // If resident, get resident data
        if ($user['role'] === 'resident') {
            $resident = $this->residentModel->where('user_id', $user['id'])->first();
            if ($resident) {
                $this->session->set('resident_id', $resident['id']);
            }
        }
        
        return redirect()->to('/dashboard');
    }

    public function register()
    {
        if ($this->session->has('user_id')) {
            return redirect()->to('/dashboard');
        }
        
        return view('auth/register');
    }

    public function createAccount()
    {
        $rules = [
            'username' => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
            'name' => 'required|alpha_numeric_space|min_length[3]|max_length[100]',
            'nik' => 'required|numeric|exact_length[16]',
            'kk' => 'required|numeric|exact_length[16]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Begin transaction
        $db = \Config\Database::connect();
        $db->transBegin();
        
        try {
            $nik = $this->request->getPost('nik');
            
            // Check if resident exists
            $resident = $this->residentModel->where('nik', $nik)->first();
            
            if ($resident) {
                // If resident exists, check if it already has a user_id
                if ($resident['user_id'] !== null) {
                    return redirect()->back()->withInput()->with('error', 'NIK ini sudah terdaftar dengan akun lain.');
                }
            }
            
            // Create user account
            $userId = $this->userModel->insert([
                'username' => $this->request->getPost('username'),
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'name' => $this->request->getPost('name'),
                'role' => 'resident',
                'status' => 'active',
            ]);
            
            if ($resident) {
                // Update existing resident with user_id
                $this->residentModel->update($resident['id'], [
                    'user_id' => $userId,
                ]);
            } else {
                // Create new resident profile
                $db = \Config\Database::connect();
                $db->table('residents')->insert([
                    'nik' => $nik,
                    'kk' => $this->request->getPost('kk'),
                    'name' => $this->request->getPost('name'),
                    'user_id' => $userId,
                    'birth_place' => $this->request->getPost('birth_place') ?? '',
                    'birth_date' => $this->request->getPost('birth_date') ?? date('Y-m-d'),
                    'gender' => $this->request->getPost('gender') ?? 'male',
                    'address' => $this->request->getPost('address') ?? '',
                    'rt' => $this->request->getPost('rt') ?? '000',
                    'rw' => $this->request->getPost('rw') ?? '000',
                    'village' => $this->request->getPost('village') ?? '',
                    'district' => $this->request->getPost('district') ?? '',
                    'religion' => $this->request->getPost('religion') ?? '',
                    'marital_status' => $this->request->getPost('marital_status') ?? 'single',
                    'occupation' => $this->request->getPost('occupation') ?? '',
                    'nationality' => $this->request->getPost('nationality') ?? 'WNI',
                    'education' => $this->request->getPost('education') ?? '',
                ]);
            }
            
            $db->transCommit();
            
            return redirect()->to('/auth/login')->with('success', 'Pendaftaran berhasil. Silakan login.');
        } catch (\Exception $e) {
            $db->transRollback();
            
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat mendaftar: ' . $e->getMessage());
        }
    }

    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }

    public function sendResetLink()
    {
        $email = $this->request->getPost('email');
        $user = $this->userModel->findByEmail($email);
        
        if (!$user) {
            return redirect()->back()->with('error', 'Email tidak ditemukan.')->withInput();
        }
        
        // Generate reset token
        $token = $this->userModel->generateResetToken($user['id']);
        
        // Send email (In a real application, you would send an actual email)
        $resetLink = base_url("auth/reset-password/{$token}");
        
        // For development purposes, we'll just show the link
        return redirect()->back()->with('success', 'Link reset password telah dikirim ke email Anda. <br>Link: <a href="' . $resetLink . '">' . $resetLink . '</a>');
    }

    public function resetPassword($token)
    {
        $user = $this->userModel->findByResetToken($token);
        
        if (!$user) {
            return redirect()->to('/auth/forgot-password')->with('error', 'Token tidak valid atau sudah kedaluwarsa.');
        }
        
        return view('auth/reset_password', ['token' => $token]);
    }

    public function updatePassword()
    {
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('password_confirm');
        
        $user = $this->userModel->findByResetToken($token);
        
        if (!$user) {
            return redirect()->to('/auth/forgot-password')->with('error', 'Token tidak valid atau sudah kedaluwarsa.');
        }
        
        if ($password !== $confirmPassword) {
            return redirect()->back()->with('error', 'Password tidak cocok.');
        }
        
        if (strlen($password) < 8) {
            return redirect()->back()->with('error', 'Password minimal 8 karakter.');
        }
        
        // Update password and clear reset token
        $this->userModel->update($user['id'], ['password' => $password]);
        $this->userModel->clearResetToken($user['id']);
        
        return redirect()->to('/auth/login')->with('success', 'Password berhasil diubah. Silakan login.');
    }

    public function logout()
    {
        $this->session->destroy();
        
        return redirect()->to('/auth/login')->with('success', 'Anda telah berhasil logout.');
    }
} 