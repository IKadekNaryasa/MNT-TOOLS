<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PeminjamanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'peminjamanCode'    => [
                'type'           => 'VARCHAR',
                'constraint' => 15,
            ],
            'requestCode'    => [
                'type'           => 'VARCHAR',
                'constraint' => 15,
            ],
            'usersId'          => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'tanggalPinjam'   => [
                'type'           => 'DATE',
            ],
            'tanggalKembali'   => [
                'type'           => 'DATE',
            ],
            'keteranganPeminjaman'     => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => true,
            ],
            'statusPeminjaman' => [
                'type'           => 'ENUM',
                'constraint'     => ['dipinjam', 'pengembalian diajukan', 'dikembalikan'],
                'default'        => 'dipinjam',
            ],
            'byAdmin'          => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'created_at'       => ['type' => 'DATE', 'default' => date('Y-m-d')],
            'updated_at'       => ['type' => 'DATE', 'default' => date('Y-m-d')],
        ]);

        $this->forge->addKey('peminjamanCode', true);
        $this->forge->addForeignKey('usersId', 'users', 'usersId', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('byAdmin', 'users', 'usersId', 'CASCADE', 'CASCADE');
        $this->forge->createTable('peminjaman');
    }

    public function down()
    {
        $this->forge->dropTable('peminjaman');
    }
}
