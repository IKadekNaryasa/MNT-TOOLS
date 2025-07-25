<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DataInventarisTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'dataInventarisId'      => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'categoryId'      => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'vendorId'      => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'tanggalDI' => [
                'type'           => 'DATE',
            ],
            'jumlahDI'            => [
                'type'           => 'INT',
                'unsigned'       => true,
                'null'        => true,
            ],
            'harga'            => [
                'type'           => 'VARCHAR',
                'constraint'     => '30',
                'null'           => true,
            ],
            'total'            => [
                'type'           => 'VARCHAR',
                'constraint'     => '30',
                'null'           => true,
            ],
            'created_at'        => ['type' => 'DATE', 'default' => date('Y-m-d')],
            'updated_at'        => ['type' => 'DATE', 'default' => date('Y-m-d')],
        ]);

        $this->forge->addKey('dataInventarisId', true);
        $this->forge->addForeignKey('categoryId', 'categories', 'categoryId', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('vendorId', 'vendors', 'vendorId', 'CASCADE', 'CASCADE');
        $this->forge->createTable('dataInventaris');
    }

    public function down()
    {
        $this->forge->dropTable('dataInventaris');
    }
}
