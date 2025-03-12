<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CategoriesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'categoryId'      => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'namaKategori'    => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
            ],
            'jumlah' => [
                'type' => 'INT',
                'insigned' => true
            ],
            'created_at'       => ['type' => 'DATE', 'default' => date('Y-m-d')],
            'updated_at'       => ['type' => 'DATE', 'default' => date('Y-m-d')],
        ]);

        $this->forge->addKey('categoryId', true);
        $this->forge->createTable('categories');
    }

    public function down()
    {
        $this->forge->dropTable('categories');
    }
}
