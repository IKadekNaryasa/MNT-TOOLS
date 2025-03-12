<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Kategori extends Seeder
{
    public function run()
    {
        $data = [
            [
                'namaKategori' => 'TANG CRIMPING',
                'jumlah' => 5,
            ],
            [
                'namaKategori' => 'OBENG',
                'jumlah' => 5,
            ],
            [
                'namaKategori' => 'SPICER',
                'jumlah' => 5,
            ],
            [
                'namaKategori' => 'OPM',
                'jumlah' => 5,
            ],
            [
                'namaKategori' => 'GUNTING',
                'jumlah' => 5,
            ],
        ];

        // Insert data ke tabel role
        $this->db->table('categories')->insertBatch($data);
    }
}
