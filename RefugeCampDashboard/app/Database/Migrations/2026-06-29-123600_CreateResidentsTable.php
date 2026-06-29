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
            'first_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'last_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'full_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255', // Broad bucket to hold 3-4 multi-part tribal or generational names
            ],
            'primary_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'backup_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'marital_status' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true, // Optional: Single, Married, Widowed, Multiple Wives, etc.
            ],
            'children_count' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 0,
                'null'       => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->createTable('residents');
    }

    public function down()
    {
        $this->forge->dropTable('residents');
    }
}
