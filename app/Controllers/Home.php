<?php

namespace App\Controllers;

use PHPUnit\Util\Json;

class Home extends BaseController
{
    public function index()
    {
        $Users = $this->Users->findAll();
        $Requests = $this->Requests->findAll();
        $Perbaikan = $this->Perbaikan->getAll();
        $Perawatan = $this->Perawatan->getAll();
        $Pengembalian = $this->Pengembalian->getAll();
        $Peminjaman = $this->Peminjaman->getAll();
        $MntTools = $this->MntTools->getAll();
        $DetailPermintaan = $this->DetailPermintaan->getAll();
        $DetailPeminjaman = $this->DetailPeminjaman->findAll();
        $DataInventaris = $this->DataInventaris->getAll();
        $Category = $this->Categories->findAll();

        return $this->response->setJSON([
            'users' => $Users,
            'request' => $Requests,
            'perbaikan' => $Perbaikan,
            'perawatan' => $Perawatan,
            'pengembalian' => $Pengembalian,
            'peminjaman' => $Peminjaman,
            'mntTools' => $MntTools,
            'detailPermintaan' => $DetailPermintaan,
            'detailPeminjaman' => $DetailPeminjaman,
            'dataInventaris' => $DataInventaris,
            'categories' => $Category,
        ]);
    }
}
