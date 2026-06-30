<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAidColumnsToActivities extends Migration
{
    public function up()
    {
       $this->forge->addColumn('activities', [
            'is_distributed_aid' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0, // 1 = Direct distribution material, 0 = Administrative cost
            ],
            'aid_category' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true, // "Water Supply", "Food Basket", "Medical Supplies", "Hygiene Kit"
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('activities', ['is_distributed_aid', 'aid_category']);
    }
}
