<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFamilyMembersTable extends Migration
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
            'resident_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'relationship_type' => [
                'type'       => 'ENUM',
                'constraint' => ['Spouse', 'Child'],
            ],
            'full_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'dob' => [
                'type' => 'DATE',
            ],
            'gender' => [
                'type'       => 'ENUM',
                'constraint' => ['Male', 'Female'],
            ],
            'has_disability' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'disability_details' => [
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
        // Add a Foreign Key constraint: if a resident profile is permanently deleted, their family table records clear automatically
        $this->forge->addForeignKey('resident_id', 'residents', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('family_members');
    }

    public function down()
    {
        $this->forge->dropTable('family_members');
    }
}
