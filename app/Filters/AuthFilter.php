<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->has('is_logged_in')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Check roles if arguments are provided
        if (!empty($arguments)) {
            $userRole = $session->get('role');
            
            // If user's role is not in the allowed roles, redirect to dashboard
            if (!in_array($userRole, $arguments)) {
                return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses untuk halaman tersebut.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing after the request
    }
} 