<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PengembalianTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'pengembalianId'  => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'peminjamanCode'    => [
                'type'           => 'VARCHAR',
                'constraint' => 15,
                'unique'    => true
            ],
            'tanggalKembali'  => [
                'type'           => 'DATE',
                'null' => true,
            ],
            'keteranganPengembalian'    => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => true,
            ],
            'byAdmin'      => [
                'type'           => 'INT',
                'unsigned'       => true,
                'null'           => true,
            ],
            'statusPengembalian' => [
                'type'           => 'ENUM',
                'constraint'     => ['diajukan', 'disetujui', 'ditolak'],
                'default'        => 'diajukan',
            ],
            'created_at'       => ['type' => 'DATE', 'default' => date('Y-m-d')],
            'updated_at'       => ['type' => 'DATE', 'default' => date('Y-m-d')],
        ]);

        $this->forge->addKey('pengembalianId', true);
        $this->forge->addForeignKey('peminjamanCode', 'peminjaman', 'peminjamanCode', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('byAdmin', 'users', 'usersId', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pengembalian');
    }

    public function down()
    {
        $this->forge->dropTable('pengembalian');
    }
}
