<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ResidentsSeeder extends Seeder
{
    public function run()
    {
        // Sample residents data
        $residents = [
            [
                'id' => 1,
                'nik' => '3305010101010001',
                'name' => 'Ahmad Suparman',
                'gender' => 'male',
                'birth_place' => 'Kebumen',
                'birth_date' => '1980-01-01',
                'religion' => 'Islam',
                'marital_status' => 'married',
                'occupation' => 'Petani',
                'address' => 'Jl. Desa No. 10 RT 03/RW 01',
                'rt' => '003',
                'rw' => '001',
                'village' => 'Desa Contoh',
                'district' => 'Kecamatan Contoh',
                'nationality' => 'WNI',
                'education' => 'SMA',
                'father_name' => 'Raden Suparno',
                'mother_name' => 'Siti Fatimah',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'nik' => '3305010101010002',
                'name' => 'Siti Aminah',
                'gender' => 'female',
                'birth_place' => 'Kebumen',
                'birth_date' => '1985-05-10',
                'religion' => 'Islam',
                'marital_status' => 'married',
                'occupation' => 'Guru',
                'address' => 'Jl. Desa No. 15 RT 02/RW 01',
                'rt' => '002',
                'rw' => '001',
                'village' => 'Desa Contoh',
                'district' => 'Kecamatan Contoh',
                'nationality' => 'WNI',
                'education' => 'S1',
                'father_name' => 'Abdul Rahman',
                'mother_name' => 'Siti Aisyah',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'nik' => '3305010101010003',
                'name' => 'Budi Santoso',
                'gender' => 'male',
                'birth_place' => 'Kebumen',
                'birth_date' => '1990-08-15',
                'religion' => 'Islam',
                'marital_status' => 'single',
                'occupation' => 'Wiraswasta',
                'address' => 'Jl. Desa No. 20 RT 05/RW 02',
                'rt' => '005',
                'rw' => '002',
                'village' => 'Desa Contoh',
                'district' => 'Kecamatan Contoh',
                'nationality' => 'WNI',
                'education' => 'SMA',
                'father_name' => 'Eko Santoso',
                'mother_name' => 'Dewi Sartika',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert data into residents table
        $this->db->table('residents')->insertBatch($residents);
    }
} 