<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Vendor extends BaseController
{
    public function index()
    {
        $data = [
            'vendors' => $this->vendor->findAll(),
            'active' => 'vendor'
        ];

        // return $this->response->setJSON($data);
        return view('admin/vendors/index', $data);
    }

    public function store()
    {
        $data = [
            'namaVendor' => $this->request->getPost('namaVendor', FILTER_SANITIZE_STRING),
        ];

        $jumlah = 0;

        $validationRules = [
            'namaVendor' => [
                'label' => 'namaVendor',
                'rules' => 'required',
                'errors' => [
                    'required' => 'nama Vendor required!',
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            $errors = $this->validator->getErrors();
            return redirect()->to(base_url('admin/vendor'))->with('errors', $errors)->withInput();
        }

        $existsVendor = $this->vendor->where('vendor', $data['namaVendor'])->first();
        if ($existsVendor) {
            return redirect()->to(base_url('admin/vendor'))->with('messages_error', 'Vendor already exists')->withInput();
        }

        $query = $this->vendor->insert(
            [
                'vendor' => strtoupper($data['namaVendor']),
            ]
        );

        if (!$query) {
            return redirect()->to(base_url('admin/vendor'))->with('messages_error', 'Failed to create new vendor');
        }

        return redirect()->to(base_url('admin/vendor'))->with('messages', 'Success create new vendor');
    }


    public function viewUpdate($encrypted_id)
    {
        $decrypted_id = decrypt_id($encrypted_id);
        $dataVendor = $this->vendor->where('vendorId', $decrypted_id)->first();
        $data = [
            'vendor' => $dataVendor,
            'active' => 'vendor'
        ];

        // return $this->response->setJSON($data);
        return view('admin/vendors/vendorUpdate', $data);
    }

    public function update()
    {


        $validationRules = [
            'vendor' => [
                'label' => 'vendor',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Vendor requireq!',
                ]
            ],
            'vendorId' => [
                'label' => 'vendorId',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Vendor to update not valid!'
                ]
            ]
        ];

        $decrypted_id = decrypt_id($this->request->getPost('vendorId'));
        $data = [
            'vendor' => $this->request->getPost('vendor'),
            'vendorId' => $decrypted_id,
        ];

        if (!$this->validate($validationRules)) {
            $errors = $this->validator->getErrors();
            return redirect()->back()->with('errors', $errors)->withInput();
        }

        $exists = $this->vendor->where('vendor', $data['vendor'])->where('vendorId != ', $data['vendorId'])->first();

        if ($exists) {
            return redirect()->back()->with('messages_error', 'Nama Vendor already exists!')->withInput();
        }

        $query = $this->vendor->update($data['vendorId'], [
            'vendor' => strtoupper($data['vendor']),
        ]);

        if (!$query) {
            return redirect()->back()->with('messages_error', 'Failed to Update vendor')->withInput();
        }
        return redirect()->to(base_url('admin/vendor'))->with('messages', 'Success update vendor');
    }

    public function delete()
    {
        $encrypted_vendorId = $this->request->getPost('vendorId', FILTER_SANITIZE_STRING);
        $vendorId = decrypt_id($encrypted_vendorId);

        $validationRules = [
            'vendorId' => [
                'label' => 'vendorId',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Vendor not valid!'
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            $error = $this->validator->getErrors();
            return redirect()->to(base_url('admin/vendor'))->with('errors', $error);
        }

        $query = $this->vendor->delete($vendorId);

        if (!$query) {
            return redirect()->to(base_url('admin/vendor'))->with('messages_error', 'Failed to delete!');
        }

        return redirect()->to(base_url('admin/vendor'))->with('messages', 'Success delete data!');
    }
}
