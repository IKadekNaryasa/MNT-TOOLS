<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    public function index()
    {
        $dataInventaris = $this->DataInventaris->countAllResults();
        $pengajuanPeminjaman = $this->Requests->countAllResults();
        $pengajuanPengembalian = $this->Pengembalian->where('statusPengembalian', 'diajukan')->countAllResults();
        $kategori = $this->Categories->countAllResults();
        $inventory = $this->MntTools->countAllResults();
        $tersedia = $this->MntTools->where('status', 'tersedia')->countAllResults();
        $perbaikan = $this->MntTools->where('status', 'perbaikan')->countAllResults();
        $perawatan = $this->MntTools->where('status', 'perawatan')->countAllResults();
        $dipinjam = $this->MntTools->where('status', 'dipinjam')->countAllResults();
        $rusak = $this->MntTools->where('status', 'rusak')->countAllResults();
        $peminjaman = $this->Peminjaman->where('statusPeminjaman', 'dipinjam')->countAllResults();
        $pengembalian = $this->Pengembalian->where('statusPengembalian', 'disetujui')->countAllResults();
        $admin = $this->Users->where('role', 'admin')->countAllResults();
        $teknisi = $this->Users->where('role', 'teknisi')->countAllResults();

        // dd($pengadaan, $kategori, $inventory, $tersedia, $perbaikan, $perawatan, $dipinjam, $rusak, $admin, $teknisi);

        $data = [
            'dataInventaris' => $dataInventaris,
            'pengajuanPeminjaman' => $pengajuanPeminjaman,
            'pengajuanPengembalian' => $pengajuanPengembalian,
            'kategori' => $kategori,
            'inventory' => $inventory,
            'tersedia' => $tersedia,
            'perbaikan' => $perbaikan,
            'perawatan' => $perawatan,
            'dipinjam' => $dipinjam,
            'rusak' => $rusak,
            'peminjaman' => $peminjaman,
            'pengembalian' => $pengembalian,
            'admin' => $admin,
            'teknisi' => $teknisi,
            'active' => 'dashboard'
        ];
        return view('admin/dashboard/index', $data);
    }
}
