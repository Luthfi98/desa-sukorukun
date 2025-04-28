<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ComplaintsSeeder extends Seeder
{
    public function run()
    {
        // Sample complaints data
        $complaints = [
            [
                'resident_id' => 1,
                'subject' => 'Kerusakan Jalan',
                'description' => 'Terdapat kerusakan jalan yang cukup parah di Jalan Desa RT 03. Mohon segera diperbaiki agar tidak membahayakan pengguna jalan.',
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
            [
                'resident_id' => 2,
                'subject' => 'Lampu Jalan Tidak Berfungsi',
                'description' => 'Lampu jalan di sekitar balai desa tidak berfungsi sejak 2 minggu yang lalu. Hal ini membuat jalan menjadi gelap dan rawan kejahatan.',
                'status' => 'processing',
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'resident_id' => 3,
                'subject' => 'Saluran Air Tersumbat',
                'description' => 'Saluran air di RT 05 tersumbat dan menyebabkan banjir saat hujan. Mohon dibersihkan segera untuk mencegah banjir yang lebih parah.',
                'status' => 'completed',
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
            ],
        ];

        // Insert data into complaints table
        $this->db->table('complaints')->insertBatch($complaints);
        
        // Get the IDs of the inserted complaints
        $lampuJalanComplaint = $this->db->table('complaints')
            ->where('subject', 'Lampu Jalan Tidak Berfungsi')
            ->get()
            ->getRow();
            
        $saluranAirComplaint = $this->db->table('complaints')
            ->where('subject', 'Saluran Air Tersumbat')
            ->get()
            ->getRow();
        
        // Make sure complaints were found
        if (!$lampuJalanComplaint || !$saluranAirComplaint) {
            return;
        }

        // Get admin user id
        $adminUser = $this->db->table('users')
            ->where('username', 'admin')
            ->get()
            ->getRow();
            
        if (!$adminUser) {
            return;
        }

        // Sample responses data using the fetched complaint IDs
        $responses = [
            [
                'complaint_id' => $lampuJalanComplaint->id,
                'user_id' => $adminUser->id, // Admin response
                'response' => 'Terima kasih atas laporannya. Kami sudah menjadwalkan perbaikan lampu jalan dan akan segera dilaksanakan dalam 2-3 hari ke depan.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'complaint_id' => $saluranAirComplaint->id,
                'user_id' => $adminUser->id, // Admin response
                'response' => 'Kami telah mengirimkan tim untuk membersihkan saluran air yang tersumbat. Pekerjaan sudah selesai dilakukan hari ini.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
            [
                'complaint_id' => $saluranAirComplaint->id,
                'user_id' => $adminUser->id, // Also admin response (changed from resident)
                'response' => 'Terima kasih atas respon cepatnya. Saluran air sudah bersih dan tidak banjir lagi ketika hujan.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
            ],
        ];

        // Insert data into complaint_responses table
        $this->db->table('complaint_responses')->insertBatch($responses);
    }
} 