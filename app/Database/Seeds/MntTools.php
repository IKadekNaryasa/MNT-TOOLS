<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MntTools extends Seeder
{
    public function run()
    {
        $categories = [
            1 => 'Tang Crimping',
            2 => 'Obeng',
            3 => 'Splicer',
            4 => 'OPM',
            5 => 'Gunting',
        ];

        $data = [];

        foreach ($categories as $id => $name) {
            $words = explode(' ', $name);
            if (count($words) > 1) {
                $prefix = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
            } else {
                $prefix = strtoupper(substr($words[0], 0, 2));
            }
            $prefix .= $id;

            for ($i = 1; $i <= 5; $i++) {
                $data[] = [
                    'namaAlat'  => $name . ' ' . $i,
                    'kodeAlat'  => sprintf('%s%03d', $prefix, $i),
                    'kondisi'    => 'Baik',
                    'status'     => 'tersedia',
                    'categoryId' => $id,
                ];
            }
        }

        $this->db->table('mntTools')->insertBatch($data);
    }
}
