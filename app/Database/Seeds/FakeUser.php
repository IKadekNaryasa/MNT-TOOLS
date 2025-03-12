<?php

namespace App\Database\Seeds;

use Faker\Factory;
use CodeIgniter\Database\Seeder;

class FakeUser extends Seeder
{
    public function run()
    {
        $faker = Factory::create('id_ID');

        $admin = [
            [
                'username'         => "arix.widiani",
                'password'         => password_hash('12345678', PASSWORD_DEFAULT),
                'role'          => 'admin',
                'nama'             => "Luh Arix Widiani",
                'kontak'           => '087864536144',
                'status'           => 'active',
                'password_updated' => null,
            ],
            [
                'username'         => "arix.teknisi",
                'password'         => password_hash('12345678', PASSWORD_DEFAULT),
                'role'          => 'teknisi',
                'nama'             => "Luh Arix Widiani",
                'kontak'           => '087864536144',
                'status'           => 'active',
                'password_updated' => null,
            ],
            [
                'username'         => "chitos.ikn",
                'password'         => password_hash('12345678', PASSWORD_DEFAULT),
                'role'          => 'admin',
                'nama'             => "I Kadek Naryasa",
                'kontak'           => '087864365344',
                'status'           => 'active',
                'password_updated' => null,
            ],
            [
                'username'         => "chitos.teknisi",
                'password'         => password_hash('12345678', PASSWORD_DEFAULT),
                'role'          => 'teknisi',
                'nama'             => "I Kadek Naryasa",
                'kontak'           => '087864365344',
                'status'           => 'active',
                'password_updated' => null,
            ],
            [
                'username'         => "chitos.ikn2",
                'password'         => password_hash('12345678', PASSWORD_DEFAULT),
                'role'          => 'teknisi',
                'nama'             => "I Kadek Naryasa",
                'kontak'           => '087864365344',
                'status'           => 'suspend',
                'password_updated' => null,
            ],
            [
                'username'         => "head.divisi",
                'password'         => password_hash('12345678', PASSWORD_DEFAULT),
                'role'          => 'head',
                'nama'             => "Head Divisi 1",
                'kontak'           => '08786436232',
                'status'           => 'active',
                'password_updated' => null,
            ],
        ];

        // Insert batch data ke tabel users

        $this->db->table('users')->insertBatch($admin);
    }
}
