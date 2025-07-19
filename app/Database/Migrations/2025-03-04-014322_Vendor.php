<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Vendor extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'vendorId'      => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'vendor'    => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
            ],
            'created_at'       => ['type' => 'DATE', 'default' => date('Y-m-d')],
            'updated_at'       => ['type' => 'DATE', 'default' => date('Y-m-d')],
        ]);

        $this->forge->addKey('vendorId', true);
        $this->forge->createTable('vendors');
    }

    public function down()
    {
        $this->forge->dropTable('vendors');
    }
}
