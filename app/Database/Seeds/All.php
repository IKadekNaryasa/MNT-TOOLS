<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class All extends Seeder
{
    public function run()
    {
        $this->call('FakeUser');
        $this->call('Kategori');
        $this->call('DataInventaris');
        $this->call('MntTools');
    }
}
