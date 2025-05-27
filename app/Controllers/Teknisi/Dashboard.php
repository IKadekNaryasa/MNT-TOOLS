<?php

namespace App\Controllers\Teknisi;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    public function index()
    {
        $data = [
            'pengajuanPeminjaman' => $this->Requests->where('usersId', decrypt_id(session('usersId')))->countAllResults(),
            'pengajuanPengembalian' => $this->Pengembalian->where('statusPengembalian', 'diajukan')->countAllResults(),
            'jumlahPeminjaman' => $this->Peminjaman->where('usersId', decrypt_id(session('usersId')))->countAllResults(),
            'jumlahPengembalian' => $this->Pengembalian->getCountById(decrypt_id(session('usersId')), 'disetujui'),
            'active' => 'dashboard'
        ];

        return view('teknisi/dashboard/index', $data);
    }
}
