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

        if ($data['harga']) {
            $data['total'] = $data['harga'] * $data['jumlahDI'];
        }

        if ($data['total']) {
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
}
