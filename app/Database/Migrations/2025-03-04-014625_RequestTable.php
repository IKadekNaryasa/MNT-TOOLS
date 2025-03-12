<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RequestTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'permintaanCode'    => [
                'type'           => 'VARCHAR',
                'constraint'       => 15,
            ],
            'usersId'          => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'status'           => [
                'type'           => 'ENUM',
                'constraint'     => ['pending', 'disetujui', 'ditolak'],
                'default'        => 'pending',
            ],
            'tanggalPermintaan' => [
                'type'           => 'DATE',
            ],
            'created_at'       => ['type' => 'DATE', 'default' => date('Y-m-d')],
            'updated_at'       => ['type' => 'DATE', 'default' => date('Y-m-d')],
        ]);

        $this->forge->addKey('permintaanCode', true);
        $this->forge->addForeignKey('usersId', 'users', 'usersId', 'CASCADE', 'CASCADE');
        $this->forge->createTable('request');
    }
    public function down()
    {
        $this->forge->dropTable('request');
    }
}
