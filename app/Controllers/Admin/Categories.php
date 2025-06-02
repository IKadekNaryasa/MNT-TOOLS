<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Category;
use CodeIgniter\HTTP\ResponseInterface;

class Categories extends BaseController
{
    public function index()
    {
        $data = [
            'categories' => $this->Categories->findAll(),
            'active' => 'kategori'
        ];

        // return $this->response->setJSON($data);
        return view('admin/categories/index', $data);
    }

    public function store()
    {
        $data = [
            'namaKategori' => $this->request->getPost('namaKategori', FILTER_SANITIZE_STRING),
        ];

        $jumlah = 0;

        $validationRules = [
            'namaKategori' => [
                'label' => 'namaKategori',
                'rules' => 'required',
                'errors' => [
                    'required' => 'nama kategori required!',
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            $errors = $this->validator->getErrors();
            return redirect()->to(base_url('admin/categories'))->with('errors', $errors)->withInput();
        }

        $existsKategori = $this->Categories->where('namaKategori', $data['namaKategori'])->first();
        if ($existsKategori) {
            return redirect()->to(base_url('admin/categories'))->with('messages_error', 'Kategori already exists')->withInput();
        }

        $query = $this->Categories->insert(
            [
                'namaKategori' => strtoupper($data['namaKategori']),
                'jumlah' => $jumlah,
            ]
        );

        if (!$query) {
            return redirect()->to(base_url('admin/categories'))->with('messages_error', 'Failed to create new kategori');
        }

        return redirect()->to(base_url('admin/categories'))->with('messages', 'Success create new kategori');
    }


    public function viewUpdate($encrypted_id)
    {
        $decrypted_id = decrypt_id($encrypted_id);
        $dataKategori = $this->Categories->where('categoryId', $decrypted_id)->first();
        $data = [
            'categories' => $dataKategori,
            'active' => 'category'
        ];

        // return $this->response->setJSON($data);
        return view('admin/categories/categoryUpdate', $data);
    }


    public function update()
    {
        // dd($_POST);
        $decrypted_id = decrypt_id($this->request->getPost('categoryId'));
        $data = [
            'namaKategori' => $this->request->getPost('namaKategori'),
            'categoryId' => $decrypted_id,
        ];

        $validationRules = [
            'namaKategori' => [
                'label' => 'namaKategori',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Kategori requireq!',
                ]
            ],
            'categoryId' => [
                'label' => 'kategori_name',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kategori to update not valid!'
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            $errors = $this->validator->getErrors();
            return redirect()->back()->with('errors', $errors)->withInput();
        }

        $exists = $this->Categories->where('namaKategori', $data['namaKategori'])->where('categoryId != ', $data['categoryId'])->first();

        if ($exists) {
            return redirect()->back()->with('messages_error', 'Nama kategori already exists!')->withInput();
        }

        $query = $this->Categories->update($data['categoryId'], [
            'namaKategori' => strtoupper($data['namaKategori']),
        ]);

        if (!$query) {
            return redirect()->back()->with('messages_error', 'Failed to Update kategori')->withInput();
        }
        return redirect()->to(base_url('admin/categories'))->with('messages', 'Success update kategori');
    }
}
