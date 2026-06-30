<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDocumentIdToResidents extends Migration
{
    public function up()
    {
       $this->forge->addColumn('residents', [
            'document_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true, // Nullable in case someone arrives with no paperwork
                'after'      => 'id'   // Puts it right at the top of your table structure
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('residents', 'document_id');
    }
}
