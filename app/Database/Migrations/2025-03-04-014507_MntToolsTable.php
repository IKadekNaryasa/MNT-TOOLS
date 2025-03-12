<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MntToolsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'mntToolsId'     => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'namaAlat'        => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
            ],
            'kodeAlat'        => [
                'type'           => 'VARCHAR',
                'constraint'     => 20,
                'unique'         => true,
            ],
            'kondisi'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'default'           => 'Baik',
            ],
            'status'          => [
                'type'           => 'ENUM',
                'constraint'     => ['tersedia', 'dipinjam', 'perawatan', 'perbaikan', 'rusak'],
                'default'        => 'tersedia',
            ],
            'categoryId'      => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'created_at'       => ['type' => 'DATE', 'default' => date('Y-m-d')],
            'updated_at'       => ['type' => 'DATE', 'default' => date('Y-m-d')],
        ]);

        $this->forge->addKey('mntToolsId', true);
        $this->forge->addForeignKey('categoryId', 'categories', 'categoryId', 'CASCADE', 'CASCADE');
        $this->forge->createTable('mntTools');
    }

    public function down()
    {
        $this->forge->dropTable('mntTools');
    }
}
