<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PerbaikanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'perbaikanId'      => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'mntToolsId'      => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'tanggalPerbaikan' => [
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
            'statusPerbaikan' => [
                'type'           => 'ENUM',
                'constraint'     => ['on progres', 'selesai', 'rusak'],
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

        $this->forge->addKey('perbaikanId', true);
        $this->forge->addForeignKey('mntToolsId', 'mntTools', 'mntToolsId', 'CASCADE', 'CASCADE');
        $this->forge->createTable('perbaikan');
    }

    public function down()
    {
        $this->forge->dropTable('perbaikan');
    }
}
