<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Pengembalian extends BaseController
{
    public function index()
    {
        $data = [
            'pengembalian' => $this->Pengembalian->getAll(),
        ];
        // return $this->response->setJSON($data);

        return view('admin/pengembalian/index', $data);
    }

    public function update()
    {
        // dd($_POST);
        $data = [
            'pengembalianId' => $this->request->getPost('pengembalianId'),
            'peminjamanCode' => $this->request->getPost('peminjamanCode'),
            'kodeAlat' => $this->request->getPost('kodeAlat'),
            'status' => $this->request->getPost('status'),
            'byAdmin' => $this->request->getPost('byAdmin'),
            'keteranganPengembalian' => $this->request->getPost('keteranganPengembalian'),
        ];

        $validationRules = [
            'pengembalianId' => [
                'label' => 'pengembalianId',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pengembalian Not Found!'
                ]
            ],
            'peminjamanCode' => [
                'label' => 'peminjamanCode',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Peminjaman Code Not Found!'
                ]
            ],
            'kodeAlat' => [
                'label' => 'kodeAlat',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kode Alat Code Not Found!'
                ]
            ],
            'status' => [
                'label' => 'status',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Status Not Valid!'
                ]
            ],
            'byAdmin' => [
                'label' => 'byAdmin',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Admin Not Valid!'
                ]
            ],
            'keteranganPengembalian' => [
                'label' => 'keteranganPengembalian',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Admin Not Valid!'
                ]
            ],
        ];

        $done = $this->Pengembalian->where('peminjamanCode', $data['peminjamanCode'])->where('statusPengembalian', 'disetujui')->first();
        if ($done) {
            return \redirect()->back()->with('messages_error', 'Pengembalian telah selesai');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        if (!$this->validate($validationRules)) {
            $errors = $this->validator->getErrors();
            return redirect()->to(base_url('admin/pengembalian'))->with('errors', $errors);
        }

        if ($data['status'] != 'disetujui' && $data['status'] != 'ditolak') {
            return redirect()->to(base_url('admin/pengembalian'))->with('messages_error', 'Status Not Valid!');
        }

        $userId = decrypt_id(session('usersId'));
        $statusPengembalian = $data['status'];
        if ($data['status'] == 'ditolak') {
            $statusPengembalian = 'diajukan';
        }

        $query1 = $this->Pengembalian->update($data['pengembalianId'], [
            'statusPengembalian' => $statusPengembalian,
            'byAdmin' => $userId,
            'tanggalKembali' => date('Y-m-d'),
            'keteranganPengembalian' => $data['keteranganPengembalian'],
        ]);
        // ganti byAdmin dengan $userId
        if (!$query1) {
            $db->transRollback();
            return redirect()->to(base_url('admin/pengembalian'))->with('messages_error', 'Failed Set Status Pengembalian');
        }

        $statusPeminjaman = 'dikembalikan';
        if ($data['status'] != 'disetujui') {
            $statusPeminjaman = 'dipinjam';
        }

        $query2 = $this->Peminjaman->update($data['peminjamanCode'], ['statusPeminjaman' => $statusPeminjaman]);
        if (!$query2) {
            $db->transRollback();
            return redirect()->to(base_url('admin/pengembalian'))->with('messages_error', 'Failed Set Status Peminjaman');
        }

        $statusAlat = 'tersedia';
        if ($data['status'] != 'disetujui') {
            $statusAlat = 'dipinjam';
        }

        $kodeAlatArray = array_map('trim', explode(',', $data['kodeAlat']));

        foreach ($kodeAlatArray as $kode) {
            if (empty($kode)) {
                $db->transRollback();
                return redirect()->to(base_url('admin/pengembalian'))->with('messages_error', 'Invalid Kode Alat format!');
            }

            $query3 = $this->MntTools->where('kodeAlat', $kode)->set(['status' => $statusAlat])->update();
            if (!$query3) {
                $db->transRollback();
                return redirect()->to(base_url('admin/pengembalian'))->with('messages_error', 'Failed Set Status Inventory');
            }
        }


        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to(base_url('admin/pengembalian'))->with('messages_error', 'Transaction failed. Changes rolled back!');
        }

        return redirect()->to(base_url('admin/pengembalian'))->with('messages', 'Succes Update All Status');
    }
}
