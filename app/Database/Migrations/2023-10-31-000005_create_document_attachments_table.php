<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDocumentAttachmentsTable extends Migration
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
            'letter_request_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'file_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'file_size' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addForeignKey('letter_request_id', 'letter_requests', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('document_attachments');
    }

    public function down()
    {
        $this->forge->dropTable('document_attachments');
    }
} 