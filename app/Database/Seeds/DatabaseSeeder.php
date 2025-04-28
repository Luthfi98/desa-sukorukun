<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Run the admin seeder to create admin user if needed
        $this->call('AdminSeeder');
        
        // Run the residents seeder
        $this->call('ResidentsSeeder');
        
        // Run the complaints seeder
        $this->call('ComplaintsSeeder');
    }
} 