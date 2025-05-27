<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Perbaikan extends BaseController
{
    public function index()
    {
        $data = [
            'perbaikan' => $this->Perbaikan->getAll(),
            'active' => 'perbaikan'

        ];
        // return $this->response->setJSON($data);

        return view('admin/perbaikan/index', $data);
    }

    public function update()
    {
        $perbaikanId = $this->request->getPost('perbaikanId', \FILTER_SANITIZE_NUMBER_INT);

        // Ambil data perbaikan berdasarkan ID
        $perbaikan = $this->Perbaikan->find($perbaikanId);

        if (!$perbaikan) {
            return redirect()->to(base_url('admin/perbaikan'))
                ->with('messages_error', 'Data perbaikan tidak ditemukan!')
                ->withInput();
        }
        if ($perbaikan['tanggalSelesai'] && $perbaikan['deskripsi'] && $perbaikan['biaya']) {
            return redirect()->to(base_url('admin/perbaikan'))
                ->with('messages_error', 'perbaikan sudah selesai. Tidak dapat diubah lagi!')
                ->withInput();
        }
        $data = [
            'perbaikanId' => $this->request->getPost('perbaikanId', \FILTER_SANITIZE_NUMBER_INT),
            'tanggalSelesai' => $this->request->getPost('tanggalSelesai'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'biaya' => $this->request->getPost('biaya'),
        ];

        // Format biaya menjadi Rupiah
        $data['biaya'] = 'Rp ' . number_format((int)$data['biaya'], 0, ',', '.');


        $status = 'tersedia';
        $kodeAlat = $this->request->getPost('kodeAlat');

        $validationRules = [
            'perbaikanId' => [
                'label' => 'perbaikanId',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Data perbaikan not valid!'
                ]
            ],
            'tanggalSelesai' => [
                'label' => 'tanggalSelesai',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal Selesai required!'
                ]
            ],
            'deskripsi' => [
                'label' => 'deskripsi',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Deskripsi required!'
                ]
            ],
            'biaya' => [
                'label' => 'biaya',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Biaya required!'
                ]
            ],
        ];

        if (!$this->validate($validationRules)) {
            $errors = $this->validator->getErrors();
            return redirect()->to(base_url('admin/perbaikan'))->with('errors', $errors)->withInput();
        }

        $dataUpdate = [
            'tanggalSelesai' => $data['tanggalSelesai'],
            'deskripsi' => $data['deskripsi'],
            'biaya' => $data['biaya'],
            'statusPerbaikan' => 'selesai'
        ];


        // dd($data, $dataUpdate, $kode_alat, $status);

        $db = \Config\Database::connect();
        $db->transStart();
        $query1 = $this->Perbaikan->update($data['perbaikanId'], $dataUpdate);
        if (!$query1) {
            $db->transRollback();
            return redirect()->to(base_url('admin/perbaikan'))->with('messages_error', 'Failed to Update perbaikan')->withInput();
        }

        // dd($this->inventoryModel->where('kode_alat', $kode_alat)->first());
        $query = $this->MntTools->where('kodeAlat', $kodeAlat)->set(['status' => $status])->update();
        if (!$query) {
            $db->transRollback();
            return redirect()->to(base_url('admin/perbaikan'))->with('messages_error', 'Failed Update Inventory Status')->withInput();
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to(base_url('admin/perbaikan'))
                ->with('messages_error', 'Transaction failed. Changes rolled back.')
                ->withInput();
        }

        // dd($data['perbaikanId']);
        return redirect()->to(base_url('admin/perbaikan'))->with('messages', "tool code $kodeAlat available");
    }
}
