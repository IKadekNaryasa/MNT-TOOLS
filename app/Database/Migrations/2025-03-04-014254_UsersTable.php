<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'usersId'          => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'username'         => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
            ],
            'password'         => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'role'             => [
                'type'           => 'ENUM',
                'constraint'     => ['admin', 'teknisi', 'head'],
                'default'        => 'teknisi',
            ],
            'nama'             => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
            ],
            'kontak'           => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => true,
            ],
            'status'           => [
                'type'           => 'ENUM',
                'constraint'     => ['active', 'suspend'],
                'default' => 'active',
            ],
            'password_updated' => ['type' => 'DATE', 'null' => true],
            'created_at'       => ['type' => 'DATE', 'default' => date('Y-m-d')],
            'updated_at'       => ['type' => 'DATE', 'default' => date('Y-m-d')],
        ]);
        $this->forge->addKey('usersId', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
