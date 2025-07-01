<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Pengembalian extends BaseController
{
    public function index()
    {
        $data = [
            'pengembalian' => $this->Pengembalian->getAllWithAlat(),
            'active' => 'pengembalian'
        ];

        return view('admin/pengembalian/index', $data);
    }

    public function update()
    {
        $data = [
            'pengembalianId' => $this->request->getPost('pengembalianId'),
            'peminjamanCode' => $this->request->getPost('peminjamanCode'),
            'userId' => $this->request->getPost('userId'),
            'keteranganPengembalian' => $this->request->getPost('keteranganPengembalian'),
        ];

        $alatStatus = $this->request->getPost('alat');

        $validationRules = [
            'pengembalianId' => ['label' => 'pengembalianId', 'rules' => 'required'],
            'peminjamanCode' => ['label' => 'peminjamanCode', 'rules' => 'required'],
            'userId' => ['label' => 'userId', 'rules' => 'required'],
            'keteranganPengembalian' => ['label' => 'keteranganPengembalian', 'rules' => 'required'],
        ];

        if (!$this->validate($validationRules)) {
            $errors = $this->validator->getErrors();
            return redirect()->to(base_url('admin/pengembalian'))->with('errors', $errors);
        }

        if (!$alatStatus || !is_array($alatStatus)) {
            return redirect()->to(base_url('admin/pengembalian'))->with('messages_error', 'Data alat tidak valid!');
        }

        $alatBelumKembali = $this->MntTools
            ->join('detailPeminjaman', 'mntTools.kodeAlat = detailPeminjaman.kodeAlat')
            ->where('detailPeminjaman.peminjamanCode', $data['peminjamanCode'])
            ->where('mntTools.status', 'dipinjam')
            ->countAllResults();

        if ($alatBelumKembali == 0) {
            return redirect()->back()->with('messages_error', 'Pengembalian telah selesai untuk semua alat');
        }


        $db = \Config\Database::connect();
        $db->transStart();

        $adminId = decrypt_id(session('usersId'));

        $adaPengembalian = false;

        foreach ($alatStatus as $item) {
            $kode = trim($item['kode'] ?? '');
            $statusAlat = trim($item['status'] ?? '');

            if (empty($kode) || empty($statusAlat)) continue;

            if ($statusAlat === 'dikembalikan') {
                $this->MntTools->where('kodeAlat', $kode)->set(['status' => 'tersedia'])->update();
                $adaPengembalian = true;
            } elseif ($statusAlat === 'rusak/hilang') {
                $this->MntTools->where('kodeAlat', $kode)->set(['status' => 'rusak'])->update();
                $adaPengembalian = true;
            }
        }

        if (!$adaPengembalian) {
            $db->transRollback();
            return redirect()->to(base_url('admin/pengembalian'))->with('messages_error', 'Tidak ada alat yang dikembalikan atau rusak/hilang!');
        }

        $statusPengembalian = 'disetujui';
        $statusPeminjaman = 'dikembalikan';

        $query1 = $this->Pengembalian->update($data['pengembalianId'], [
            'statusPengembalian' => $statusPengembalian,
            'byAdmin' => $adminId,
            'tanggalKembali' => date('Y-m-d'),
            'keteranganPengembalian' => $data['keteranganPengembalian'],
        ]);
        if (!$query1) {
            $db->transRollback();
            return redirect()->to(base_url('admin/pengembalian'))->with('messages_error', 'Gagal memperbarui data pengembalian.');
        }

        $query2 = $this->Peminjaman->update($data['peminjamanCode'], ['statusPeminjaman' => $statusPeminjaman]);
        if (!$query2) {
            $db->transRollback();
            return redirect()->to(base_url('admin/pengembalian'))->with('messages_error', 'Gagal memperbarui status peminjaman.');
        }

        $query3 = $this->Users->update($data['userId'], ['status' => 'active']);
        if (!$query3) {
            $db->transRollback();
            return redirect()->to(base_url('admin/pengembalian'))->with('messages_error', 'Gagal mengaktifkan pengguna.');
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to(base_url('admin/pengembalian'))->with('messages_error', 'Transaksi gagal. Perubahan dibatalkan.');
        }

        return redirect()->to(base_url('admin/pengembalian'))->with('messages', 'Berhasil mengkonfirmasi pengembalian.');
    }
}
