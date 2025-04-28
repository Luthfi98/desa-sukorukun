<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResidentsTable extends Migration
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
            'nik' => [
                'type'       => 'VARCHAR',
                'constraint' => 16,
                'unique'     => true,
            ],
            'kk' => [
                'type'       => 'VARCHAR',
                'constraint' => 16,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'birth_place' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'birth_date' => [
                'type'       => 'DATE',
            ],
            'gender' => [
                'type'       => 'ENUM',
                'constraint' => ['male', 'female'],
            ],
            'address' => [
                'type'       => 'TEXT',
            ],
            'rt' => [
                'type'       => 'VARCHAR',
                'constraint' => 5,
            ],
            'rw' => [
                'type'       => 'VARCHAR',
                'constraint' => 5,
            ],
            'village' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'district' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'religion' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'marital_status' => [
                'type'       => 'ENUM',
                'constraint' => ['single', 'married', 'divorced', 'widowed'],
            ],
            'occupation' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'nationality' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'WNI',
            ],
            'education' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'father_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'mother_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('residents');
    }

    public function down()
    {
        $this->forge->dropTable('residents');
    }
} 