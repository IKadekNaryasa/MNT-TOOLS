<?php

namespace App\Controllers\Admin;

use Config\Database;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DataInventaris extends BaseController
{
    public function index()
    {
        $data = [
            'inventaris' => $this->DataInventaris->getAll(),
            'categories' => $this->Categories->findAll(),
        ];

        // return $this->response->setJSON($data);


        return view('admin/inventaris/index', $data);
    }

    public function store()
    {
        $data = [
            'categoryId' => $this->request->getPost('categoryId', FILTER_SANITIZE_NUMBER_INT),
            'jumlahDI' => $this->request->getPost('jumlahDI'),
            'vendor' => $this->request->getPost('vendor', FILTER_SANITIZE_STRING),
            'harga' => $this->request->getPost('harga', FILTER_SANITIZE_STRING),
            'total' => $this->request->getPost('total', FILTER_SANITIZE_STRING),
            'keterangan' => $this->request->getPost('keterangan', FILTER_SANITIZE_STRING),
        ];

        // return $this->response->setJSON($data);

        $validationRules = [
            'categoryId' => [
                'label' => 'categoryId',
                'rules' => 'required',
                'errors' => ['required' => 'kategori required!']
            ],
            'jumlahDI' => [
                'label' => 'jumlahDI',
                'rules' => 'required',
                'errors' => ['required' => 'jumlah required!']
            ],
            'vendor' => [
                'label' => 'vendor',
                'rules' => 'required',
                'errors' => ['required' => 'vendor pengadaan required!']
            ],
            'keterangan' => [
                'label' => 'keterangan',
                'rules' => 'required',
                'errors' => ['required' => 'keterangan required!']
            ],
        ];

        if (!$data['harga'] && !$data['total']) {
            return redirect()->back()->with('messages_error', 'Isikan salah satu harga')->withInput();
        }

        if (!$data['total']) {
            $data['total'] = $data['harga'] * $data['jumlahDI'];
        }

        if (!$data['harga']) {
            $data['harga'] = $data['total'] / $data['jumlahDI'];
        }

        // return $this->response->setJSON($data);

        if (!$this->validate($validationRules)) {
            $errors = $this->validator->getErrors();
            return redirect()->to(base_url('admin/inventaris'))->with('errors', $errors)->withInput();
        }

        $dataQuery = [
            'categoryId' => $data['categoryId'],
            'tanggalDI' => date('Y-m-d'),
            'jumlahDI' => $data['jumlahDI'],
            'vendor' => $data['vendor'],
            'harga' => $data['harga'],
            'total' => $data['total'],
            'keterangan' => $data['keterangan'],
        ];


        $db = Database::connect();

        $db->transBegin();

        try {
            $query = $this->DataInventaris->insert($dataQuery);
            if (!$query) {
                throw new \Exception('Failed Insert Data');
            }
            $jumlahPerkategori = $this->DataInventaris->countJumlah($dataQuery['categoryId']);

            $query2 = $this->Categories->update($data['categoryId'], ['jumlah' => $jumlahPerkategori]);
            if (!$query2) {
                throw new \Exception('Failed update kategori value');
            }
            $db->transCommit();
            return redirect()->to(base_url('admin/inventaris'))->with('messages', 'Insert Data success!');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to(base_url('admin/inventaris'))->with('messages_error', $e->getMessage())->withInput();
        }
    }

    public function viewUpdate($id)
    {
        $id = decrypt_id($id);
        $inventaris = $this->DataInventaris->find($id);
        if (!$inventaris) {
            return redirect()->back()->with('messages_error', 'Data not found!');
        }
        $data = [
            'inventaris' => $inventaris,
            'categories' => $this->Categories->findAll()
        ];
        return view('admin/inventaris/inventarisUpdate', $data);
    }

    public function update()
    {
        $data = [
            'categoryId' => $this->request->getPost('categoryId'),
            'jumlahDI' => $this->request->getPost('jumlahDI'),
            'tanggalDI' => $this->request->getPost('tanggalDI'),
            'vendor' => $this->request->getPost('vendor', FILTER_SANITIZE_STRING),
            'harga' => $this->request->getPost('harga', FILTER_SANITIZE_STRING),
            'total' => $this->request->getPost('total', FILTER_SANITIZE_STRING),
            'keterangan' => $this->request->getPost('keterangan', FILTER_SANITIZE_STRING),
        ];

        // return $this->response->setJSON($data);

        $validationRules = [
            'categoryId' => [
                'label' => 'categoryId',
                'rules' => 'required',
                'errors' => ['required' => 'kategori required!']
            ],
            'jumlahDI' => [
                'label' => 'jumlahDI',
                'rules' => 'required',
                'errors' => ['required' => 'jumlah required!']
            ],
            'vendor' => [
                'label' => 'vendor',
                'rules' => 'required',
                'errors' => ['required' => 'vendor pengadaan required!']
            ],
            'tanggalDI' => [
                'label' => 'tanggal',
                'rules' => 'required|date',
                'errors' => ['required' => 'tanggal required!']
            ],
            'keterangan' => [
                'label' => 'keterangan',
                'rules' => 'required',
                'errors' => ['required' => 'keterangan required!']
            ],
        ];

        if (!$data['harga'] && !$data['total']) {
            return redirect()->back()->with('messages_error', 'Isikan salah satu harga')->withInput();
        }

        if (!$data['total']) {
            $data['total'] = $data['harga'] * $data['jumlahDI'];
        }

        if (!$data['harga']) {
            $data['harga'] = $data['total'] / $data['jumlahDI'];
        }

        // return $this->response->setJSON($data);

        if (!$this->validate($validationRules)) {
            $errors = $this->validator->getErrors();
            return redirect()->back()->with('errors', $errors)->withInput();
        }

        $dataQuery = [
            'tanggalDI' => date('Y-m-d'),
            'jumlahDI' => $data['jumlahDI'],
            'vendor' => $data['vendor'],
            'harga' => $data['harga'],
            'total' => $data['total'],
            'keterangan' => $data['keterangan'],
        ];
        $category = decrypt_id($data['categoryId']);
        $dataInventarisId = decrypt_id($this->request->getPost('dataInventarisId'));

        $db = Database::connect();

        $db->transBegin();

        try {

            $dataKategori = $this->Categories->find($category);

            $jumlahPerkategori = $dataKategori['jumlah'];
            $oldData = $this->DataInventaris->find($dataInventarisId);
            $oldJumlah = $oldData['jumlahDI'];
            $newJjumlah = $dataQuery['jumlahDI'];


            $jumlah = ($jumlahPerkategori - $oldJumlah) + $newJjumlah;

            // return $this->response->setJSON($jumlah);

            if ($jumlah < 0) {
                throw new \Exception('Jumlah tidak valid. Jumlah total kategori tidak boleh negatif.');
            }
            $query = $this->DataInventaris->update($dataInventarisId, $dataQuery);
            if (!$query) {
                throw new \Exception('Failed update Data');
            }

            $query2 = $this->Categories->update($category, ['jumlah' => $jumlah]);
            if (!$query2) {
                throw new \Exception('Failed update kategori value');
            }
            $db->transCommit();
            return redirect()->to(base_url('admin/inventaris'))->with('messages', 'Insert Data success!');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('messages_error', $e->getMessage())->withInput();
        }
    }
}
