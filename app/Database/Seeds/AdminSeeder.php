<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();
        
        // Check if admin already exists
        $existingAdmin = $userModel->where('username', 'admin')->first();
        
        if (!$existingAdmin) {
            // Create admin user
            $userModel->insert([
                'username' => 'admin',
                'email'    => 'admin@admin.com',
                'password' => 'admin123',
                'name'     => 'Administrator',
                'role'     => 'admin',
                'status'   => 'active'
            ]);
            
            echo "Admin user created successfully.\n";
        } else {
            echo "Admin user already exists.\n";
        }
    }
} 