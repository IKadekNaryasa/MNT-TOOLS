<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PerawatanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'perawatanId'      => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'mntToolsId'      => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'tanggalPerawatan' => [
                'type'           => 'DATE',
            ],
            'tanggalSelesai' => [
                'type'           => 'DATE',
                'default'           => null,
            ],
            'deskripsi'         => [
                'type'           => 'TEXT',
                'null'           => true,
            ],
            'statusPerawatan' => [
                'type'           => 'ENUM',
                'constraint'     => ['on progres', 'selesai',],
                'default'        => 'on progres',
            ],
            'biaya'             => [
                'type'           => 'VARCHAR',
                'constraint'     => '30',
                'null'           => true,
            ],
            'created_at'        => ['type' => 'DATE', 'default' => date('Y-m-d')],
            'updated_at'        => ['type' => 'DATE', 'default' => date('Y-m-d')],
        ]);

        $this->forge->addKey('perawatanId', true);
        $this->forge->addForeignKey('mntToolsId', 'mntTools', 'mntToolsId', 'CASCADE', 'CASCADE');
        $this->forge->createTable('perawatan');
    }

    public function down()
    {
        $this->forge->dropTable('perawatan');
    }
}
