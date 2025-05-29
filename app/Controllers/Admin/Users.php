<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Users extends BaseController
{
    public function index()
    {
        $data = [
            'users' => $this->Users->findAll(),
            'active' => 'user'

        ];
        // return $this->response->setJSON($data);

        return view('admin/users/index', $data);
    }

    public function store()
    {
        $data = [
            'nama' => $this->request->getPost('nama', FILTER_SANITIZE_STRING),
            'username' => $this->request->getPost('username', FILTER_SANITIZE_STRING),
            'kontak' => $this->request->getPost('kontak', FILTER_SANITIZE_STRING),
            'role' => $this->request->getPost('role', FILTER_SANITIZE_STRING),
        ];
        $password = password_hash('12345678', PASSWORD_BCRYPT);

        $validationRules = [
            'nama' => [
                'label' => 'nama',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama required!',
                ]
            ],
            'username' => [
                'label' => 'username',
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Username required!',
                    'min_length' => 'Username must be at least 8 character long'
                ]
            ],
            'kontak' => [
                'label' => 'kontak',
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Kontak required!',
                    'min_length' => 'Kontak must be at least 8 character long'
                ]
            ],
            'role' => [
                'label' => 'role',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Role required!',
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            $erors = $this->validator->getErrors();
            return redirect()->back()->with('errors', $erors);
        }

        $userExists = $this->Users->where('username', $data['username'])->first();
        if ($userExists) {
            return redirect()->back()->with('messages_error', 'Username already exists!')->withInput();
        }

        $dataInsert = [
            'username' => $data['username'],
            'password' => $password,
            'role' => $data['role'],
            'nama' => $data['nama'],
            'status' => 'active',
            'kontak' => $data['kontak'],
            'password_updated' => null,
        ];

        $query = $this->Users->insert($dataInsert);

        if (!$query) {
            return redirect()->back()->with('messages_error', 'Failed create new user!')->withInput();
        }

        return redirect()->back()->with('messages', 'Success create new user!');
    }

    public function viewUpdate($usersId)
    {

        $user = $this->Users->find(decrypt_id($usersId));
        $data = [
            'users' => $user,
            'active' => 'user'
        ];
        // dd($data['users']);
        return view('admin/users/userUpdate', $data);
    }

    public function update()
    {
        $usersId_encrypted =  $this->request->getPost('usersId', FILTER_SANITIZE_STRING);
        $usersId = decrypt_id($usersId_encrypted);
        $data = [
            'usersId' => $usersId,
            'username' => $this->request->getPost('username', FILTER_SANITIZE_STRING),
            'nama' => $this->request->getPost('nama', FILTER_SANITIZE_STRING),
            'kontak' => $this->request->getPost('kontak', FILTER_SANITIZE_STRING),
            'status' => $this->request->getPost('status', \FILTER_SANITIZE_STRING),
        ];
        $validationRules = [
            'usersId' => [
                'label' => 'usersId',
                'rules' => 'required',
                'errors' => [
                    'required' => 'User Not Valid',
                ]
            ],
            'username' => [
                'label' => 'username',
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Username Required',
                    'min_length' => 'Username must be at least 8 characters long',
                ]
            ],
            'nama' => [
                'label' => 'nama',
                'rules' => 'required|min_length[5]',
                'errors' => [
                    'required' => 'Name Required',
                    'min_length' => 'Name must be at least 5 characters long',
                ]
            ],
            'kontak' => [
                'label' => 'kontak',
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Contact Required',
                    'min_length' => 'Contact must be at least 8 characters long',
                ]
            ],
            'status' => [
                'label' => 'status',
                'rules' => 'required',
                'errors' => [
                    'required' => 'status Required',
                ]
            ],
        ];

        if (!$this->validate($validationRules)) {
            $error = $this->validator->getErrors();
            return redirect()->back()->with('error', $error)->withInput();
        }

        $exists = $this->Users->where('username', $data['username'])->where('usersId !=', $data['usersId'])->first();

        if ($exists) {
            return redirect()->back()->with('messages_error', 'Username already exists')->withInput();
        }

        $dataUpdate = [
            'username' => $data['username'],
            'nama' => $data['nama'],
            'kontak' => $data['kontak'],
            'status' => $data['status'],
        ];


        $query = $this->Users->update($data['usersId'], $dataUpdate);

        if (!$query) {
            return redirect()->back()->with('messages_error', 'Failed to update user')->withInput();
        }

        return redirect()->to(base_url('admin/users'))->with('messages', 'Update data Success!');
    }

    public function delete()
    {
        $usersId_encrypted = $this->request->getPost('usersId', FILTER_SANITIZE_STRING);
        $usersId = decrypt_id($usersId_encrypted);
        $validationRules = [
            'usersId' => [
                'label' => 'usersId',
                'rules' => 'required',
                'errors' => [
                    'required' => 'User Id Not Valid!'
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            $error = $this->validator->getErrors();
            return redirect()->to(base_url('admin/users'))->with('errors', $error);
        }

        $query = $this->Users->delete($usersId);

        if (!$query) {
            return redirect()->to(base_url('admin/users'))->with('messages_error', 'Failed Delete User!');
        }

        return redirect()->to(base_url('admin/users'))->with('messages', 'Delete User Success!');
    }

    public function profile()
    {
        $data = [
            'active' => ''
        ];
        return \view('admin/profile', $data);
    }
}
