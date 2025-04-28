<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToLetterTypes extends Migration
{
    public function up()
    {
        // Add status column if it doesn't exist
        if (!$this->db->fieldExists('status', 'letter_types')) {
            $this->forge->addColumn('letter_types', [
                'status' => [
                    'type'       => 'ENUM',
                    'constraint' => ['active', 'inactive'],
                    'default'    => 'active',
                    'after'      => 'description'
                ]
            ]);
        }

        // Add deleted_at column for soft deletes if it doesn't exist
        if (!$this->db->fieldExists('deleted_at', 'letter_types')) {
            $this->forge->addColumn('letter_types', [
                'deleted_at' => [
                    'type'    => 'DATETIME',
                    'null'    => true,
                    'after'   => 'updated_at'
                ]
            ]);
        }
    }

    public function down()
    {
        // Drop the columns if they exist
        if ($this->db->fieldExists('status', 'letter_types')) {
            $this->forge->dropColumn('letter_types', 'status');
        }
        
        if ($this->db->fieldExists('deleted_at', 'letter_types')) {
            $this->forge->dropColumn('letter_types', 'deleted_at');
        }
    }
} 