<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActivityResidentsTable extends Migration
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
            'activity_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'resident_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['activity_id', 'resident_id']);

        // Added formal foreign keys to keep Aiven clean
        $this->forge->addForeignKey('activity_id', 'activities', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('resident_id', 'residents', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('activity_residents');
    }

    public function down()
    {
        $this->forge->dropTable('activity_residents');
    }
}