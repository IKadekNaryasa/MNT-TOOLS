<?php

namespace App\Controllers\Teknisi;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Peminjaman extends BaseController
{
    public function index()
    {

        $data = [
            'peminjaman' => $this->Peminjaman->getDataByUser(\decrypt_id(\session('usersId'))),
            'active' => 'peminjaman'

        ];

        return \view('teknisi/peminjaman/index', $data);
    }
}
