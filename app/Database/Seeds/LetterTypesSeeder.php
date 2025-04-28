<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LetterTypesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Surat Keterangan Pindah Desa',
                'code' => 'SKPD',
                'description' => 'Surat keterangan yang menyatakan seseorang pindah dari desa',
                'required_documents' => 'KTP, KK, Surat pengantar RT/RW',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Surat Keterangan Kelahiran',
                'code' => 'SKK',
                'description' => 'Surat keterangan yang menyatakan kelahiran seseorang',
                'required_documents' => 'KK, Akta kelahiran, Surat keterangan dari bidan/rumah sakit',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Surat Keterangan Ahli Waris',
                'code' => 'SKAW',
                'description' => 'Surat keterangan yang menyatakan ahli waris seseorang',
                'required_documents' => 'KTP, KK, Akta kematian, Surat wasiat (jika ada)',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Surat Keterangan Usaha',
                'code' => 'SKU',
                'description' => 'Surat keterangan yang menyatakan bahwa seseorang memiliki usaha di wilayah desa',
                'required_documents' => 'KTP, KK, Foto usaha, Surat keterangan RT/RW',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Surat Keterangan Domisili',
                'code' => 'SKD',
                'description' => 'Surat keterangan yang menyatakan domisili seseorang di wilayah desa',
                'required_documents' => 'KTP, KK, Surat pengantar RT/RW',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Surat Keterangan Kematian',
                'code' => 'SKKM',
                'description' => 'Surat keterangan yang menyatakan kematian seseorang',
                'required_documents' => 'KTP, KK, Surat keterangan dari rumah sakit/bidan',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Surat Keterangan Tidak Mampu',
                'code' => 'SKTM',
                'description' => 'Surat keterangan yang menyatakan bahwa seseorang tergolong tidak mampu secara ekonomi',
                'required_documents' => 'KTP, KK, Foto tempat tinggal, Surat pengantar RT/RW',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Surat Keterangan Belum Menikah',
                'code' => 'SKBM',
                'description' => 'Surat keterangan yang menyatakan bahwa seseorang belum menikah',
                'required_documents' => 'KTP, KK, Surat pengantar RT/RW',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Surat Keterangan Kehilangan KTP & KK',
                'code' => 'SKKK',
                'description' => 'Surat keterangan yang menyatakan kehilangan KTP dan KK',
                'required_documents' => 'Surat pengantar RT/RW, Surat keterangan dari kepolisian',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Surat Memperbarui KTP & KK',
                'code' => 'SMKK',
                'description' => 'Surat untuk memperbarui KTP dan KK',
                'required_documents' => 'KTP lama, KK lama, Surat pengantar RT/RW',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Surat Keterangan Izin Karyawan/Sekolah',
                'code' => 'SKIS',
                'description' => 'Surat keterangan izin untuk karyawan atau siswa',
                'required_documents' => 'KTP, KK, Surat pengantar dari perusahaan/sekolah',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Surat Pengantar SKCK',
                'code' => 'SKCK',
                'description' => 'Surat pengantar untuk pembuatan SKCK di kepolisian',
                'required_documents' => 'KTP, KK, Pas foto, Surat pengantar RT/RW',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('letter_types')->insertBatch($data);
    }
} 