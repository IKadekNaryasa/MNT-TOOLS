<?php

namespace App\Controllers\Teknisi;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;

class Pengembalian extends BaseController
{
    public function index()
    {
        $data = [
            'pengembalian' => $this->Pengembalian->getDataByUser(\decrypt_id(\session('usersId'))),
            'active' => 'pengembalian'

        ];
        // \dd($data);
        return \view('teknisi/pengembalian/index', $data);
    }

    public function pengajuanPengembalian()
    {
        $peminjamanCode = $this->request->getPost('peminjamanCode', \FILTER_SANITIZE_STRING);

        $validationRules = [
            'peminjamanCode' => [
                'label' => 'peminjamanCode',
                'rules' => 'required',
                'errors' => [
                    'required' => 'peminjaman code not valid!'
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            return \redirect()->back()->with('errors', $this->validator->getErrors());
        }

        // dd($peminjamanCode);

        $exists = $this->Pengembalian->where('peminjamanCode', $peminjamanCode)->first();
        if ($exists) {
            return redirect()->back()->with('messages_error', 'Pengembalian sudah diajukan!');
        }

        $dataUpdate = [
            'statusPeminjaman' => 'pengembalian diajukan'
        ];
        $dataPengembalian = [
            'peminjamanCode' => $peminjamanCode,
            'statusPengembalian' => 'diajukan',
        ];

        $db = Database::connect();
        $db->transStart();

        $queryUpdatePeminjaman = $this->Peminjaman->update($peminjamanCode, $dataUpdate);
        if (!$queryUpdatePeminjaman) {
            $db->transRollback();
            return redirect()->back()->with('messages_error', 'Pengajuan Gagal!');
        }

        $querySetPengajuanPengembalian = $this->Pengembalian->insert($dataPengembalian);
        if (!$querySetPengajuanPengembalian) {
            $db->transRollback();
            return redirect()->back()->with('messages_error', 'Pengajuan Gagal!');
        }

        $db->transComplete();
        if ($db->transStatus() === false) {
            return redirect()->to(base_url('teknisi/peminjaman'))->with('messages_error', 'Pegajuan Gagal!');
        }

        return redirect()->to(base_url('teknisi/peminjaman'))->with('messages', 'Pengajuan Berhasil!');
    }
}
