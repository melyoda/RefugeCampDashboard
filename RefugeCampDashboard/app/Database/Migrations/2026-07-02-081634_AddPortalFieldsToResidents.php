<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPortalFieldsToResidents extends Migration
{
    public function up()
    {
        $this->forge->addColumn('residents', [
            'access_code_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true, // Nullable initially for existing records
                'after'      => 'document_id'
            ],
            'dob' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'full_name'
            ],
            'has_disability' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'dob'
            ],
            'disability_details' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'has_disability'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('residents', ['access_code_hash', 'dob', 'has_disability', 'disability_details']);
    }
}
