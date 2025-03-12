<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DataInventaris extends Seeder
{
    public function run()
    {
        $pengadaanData = [
            [
                'categoryId'      => 1,
                'tanggalDI' => date('Y-m-d'),
                'jumlahDI'  => 5,
                'vendor'           => 'Vendor A',
                'keterangan'       => 'Penambahan Tang untuk teknisi',
                'harga' => '5000',
                'total' => '25000',
            ],
            [
                'categoryId'      => 2,
                'tanggalDI' => date('Y-m-d'),
                'jumlahDI'           => 5,
                'vendor'           => 'Vendor B',
                'keterangan'       => 'Penambahan Obeng Phillips',
                'harga' => '5000',
                'total' => '25000',
            ],
            [
                'categoryId'      => 3,
                'tanggalDI' => date('Y-m-d'),
                'jumlahDI'           => 5,
                'vendor'           => 'Vendor C',
                'keterangan'       => 'Penambahan Splicer Fiber Optic',
                'harga' => '5000',
                'total' => '25000',
            ],
            [
                'categoryId'      => 4,
                'tanggalDI' => date('Y-m-d'),
                'jumlahDI'           => 5,
                'vendor'           => 'Vendor D',
                'keterangan'       => 'Penambahan OPM untuk pengecekan jaringan',
                'harga' => '5000',
                'total' => '25000',
            ],
            [
                'categoryId'      => 5,
                'tanggalDI' => date('Y-m-d'),
                'jumlahDI'           => 5,
                'vendor'           => 'Vendor E',
                'keterangan'       => 'Penambahan Gunting untuk peralatan dasar',
                'harga' => '5000',
                'total' => '25000',
            ],
        ];

        // Insert batch data into the database
        $this->db->table('dataInventaris')->insertBatch($pengadaanData);
    }
}
