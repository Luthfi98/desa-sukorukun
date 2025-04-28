<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLetterTypesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'unique'     => true,
            ],
            'description' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'template' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'required_documents' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('letter_types');
        
        // Insert default letter types
        $data = [
            [
                'name' => 'Surat Keterangan Domisili',
                'code' => 'SKD',
                'description' => 'Surat keterangan yang menyatakan domisili seseorang di wilayah desa',
                'required_documents' => 'KTP, KK',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Surat Keterangan Tidak Mampu',
                'code' => 'SKTM',
                'description' => 'Surat keterangan yang menyatakan bahwa seseorang tergolong tidak mampu secara ekonomi',
                'required_documents' => 'KTP, KK, Foto tempat tinggal',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Surat Pengantar SKCK',
                'code' => 'SKCK',
                'description' => 'Surat pengantar untuk pembuatan SKCK di kepolisian',
                'required_documents' => 'KTP, KK, Pas foto',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Surat Keterangan Usaha',
                'code' => 'SKU',
                'description' => 'Surat keterangan yang menyatakan bahwa seseorang memiliki usaha di wilayah desa',
                'required_documents' => 'KTP, KK, Foto usaha',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];
        
        $this->db->table('letter_types')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('letter_types');
    }
} 