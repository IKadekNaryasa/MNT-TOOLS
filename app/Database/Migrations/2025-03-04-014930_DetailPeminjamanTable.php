<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DetailPeminjamanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'detailPeminjamanId' => [
                'type' => 'INT',
                'unsigned' => true,
                'constraint' => 5,
                'auto_increment' => true,
            ],
            'peminjamanCode' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
            ],
            'kodeAlat' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'created_at'       => ['type' => 'DATE', 'default' => date('Y-m-d')],
            'updated_at'       => ['type' => 'DATE', 'default' => date('Y-m-d')],
        ]);

        $this->forge->addKey('detailPeminjamanId', true);
        $this->forge->addForeignKey('peminjamanCode', 'peminjaman', 'peminjamanCode', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('kodeAlat', 'mnttools', 'kodeAlat', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detailPeminjaman');
    }

    public function down()
    {
        $this->forge->dropTable('detailPeminjaman');
    }
}
