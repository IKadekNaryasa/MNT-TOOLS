<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DetailRequestTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'detailRequestId' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'permintaanCode' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
            ],
            'categoryId' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'created_at'       => ['type' => 'DATE', 'default' => date('Y-m-d')],
            'updated_at'       => ['type' => 'DATE', 'default' => date('Y-m-d')],
        ]);

        $this->forge->addKey('detailRequestId', true);
        $this->forge->addForeignKey('permintaanCode', 'request', 'permintaanCode', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('categoryId', 'categories', 'categoryId', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detailRequest');
    }

    public function down()
    {
        $this->forge->dropTable('detailRequest');
    }
}
