<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Perawatan extends BaseController
{
    public function index()
    {
        $data = [
            'perawatan' => $this->Perawatan->getAll(),
            'active' => 'perawatan'

        ];
        // return $this->response->setJSON($data);

        return view('admin/perawatan/index', $data);
    }

    public function update()
    {
        $perawatanId = $this->request->getPost('perawatanId', \FILTER_SANITIZE_NUMBER_INT);

        // Ambil data perawatan berdasarkan ID
        $perawatan = $this->Perawatan->find($perawatanId);

        if (!$perawatan) {
            return redirect()->to(base_url('admin/perawatan'))
                ->with('messages_error', 'Data perawatan tidak ditemukan!')
                ->withInput();
        }
        if ($perawatan['tanggalSelesai'] && $perawatan['deskripsi'] && $perawatan['biaya']) {
            return redirect()->to(base_url('admin/perawatan'))
                ->with('messages_error', 'Perawatan sudah selesai. Tidak dapat diubah lagi!')
                ->withInput();
        }
        $data = [
            'perawatanId' => $this->request->getPost('perawatanId', \FILTER_SANITIZE_NUMBER_INT),
            'tanggalSelesai' => $this->request->getPost('tanggalSelesai'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'biaya' => $this->request->getPost('biaya'),
        ];

        // Format biaya menjadi Rupiah
        $data['biaya'] = 'Rp ' . number_format((int)$data['biaya'], 0, ',', '.');


        $status = 'tersedia';
        $kodeAlat = $this->request->getPost('kodeAlat');

        $validationRules = [
            'perawatanId' => [
                'label' => 'perawatanId',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Data perawatan not valid!'
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
            return redirect()->to(base_url('admin/perawatan'))->with('errors', $errors)->withInput();
        }

        $dataUpdate = [
            'tanggalSelesai' => $data['tanggalSelesai'],
            'deskripsi' => $data['deskripsi'],
            'biaya' => $data['biaya'],
            'statusPerawatan' => 'selesai'
        ];


        // dd($data, $dataUpdate, $kode_alat, $status);

        $db = \Config\Database::connect();
        $db->transStart();
        $query1 = $this->Perawatan->update($data['perawatanId'], $dataUpdate);
        if (!$query1) {
            $db->transRollback();
            return redirect()->to(base_url('admin/perawatan'))->with('messages_error', 'Failed to Update Perawatan')->withInput();
        }

        // dd($this->inventoryModel->where('kode_alat', $kode_alat)->first());
        $query = $this->MntTools->where('kodeAlat', $kodeAlat)->set(['status' => $status])->update();
        if (!$query) {
            $db->transRollback();
            return redirect()->to(base_url('admin/perawatan'))->with('messages_error', 'Failed Update Inventory Status')->withInput();
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to(base_url('admin/perawatan'))
                ->with('messages_error', 'Transaction failed. Changes rolled back.')
                ->withInput();
        }

        // dd($data['perawatanId']);
        return redirect()->to(base_url('admin/perawatan'))->with('messages', "tool code $kodeAlat available");
    }
}
