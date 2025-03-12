<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    public function index()
    {
        return view('auth/login');
    }

    public function authenticate()
    {
        $data = [
            'username' => $this->request->getPost('username', FILTER_SANITIZE_STRING),
            'password' => $this->request->getPost('password', FILTER_SANITIZE_STRING),
        ];

        $validationRules = [
            'username' => [
                'label' => 'username',
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Username required!',
                    'min_length' => 'username must be at least 8 charachter long!',
                ]
            ],
            'password' => [
                'label' => 'password',
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'password required!',
                    'min_length' => 'password must be at least 8 charachter long!',
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            $errors = $this->validator->getErrors();
            return redirect()->to(base_url('/'))->with('errors', $errors)->withInput();
        }
        // dd($data);
        $user = $this->Users->where('username', $data['username'])->first();
        // dd($user);

        if (!$user) {
            return redirect()->to(base_url('/'))->with('messages_error', 'Invalid Username or Password')->withInput();
        }

        if (!password_verify($data['password'], $user['password'])) {
            return redirect()->to(base_url('/'))->with('messages_error', 'Invalid Username or Password')->withInput();
        }


        $this->setUserSession($user);
        session()->setFlashdata('messages', 'Login Success!');

        if ($user['password_updated'] == null) {
            return  redirect()->to(base_url('changePasswordFirst'));
        }

        if ($user['role'] == 'teknisi' && current_url() != base_url('teknisi/dashboard')) {
            return redirect()->to(base_url('teknisi/dashboard'));
        }
        if ($user['role'] == 'head' && current_url() != base_url('head/dashboard')) {
            return redirect()->to(base_url('head/dashboard'));
        }
        if (current_url() != base_url('admin/dashboard')) {
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    private function setUserSession($user)
    {
        $usersId = encrypt_id($user['usersId']);
        $data = [
            'usersId' => $usersId,
            'nama' => $user['nama'],
            'username' => $user['username'],
            'kontak' => $user['kontak'],
            'status' => $user['status'],
            'role' => $user['role'],
            'logged_in' => true,
        ];
        session()->set($data);
        return true;
    }

    public function logout()
    {
        if (!session('logged_in')) {
            return redirect()->to(base_url('/'))->with('messages_error', 'you are not logged in!');
        }

        session()->remove(['usersId', 'nama', 'kontak', 'role', 'logged_in', 'status', 'UID', 'Uname', 'Uusername', 'cart']);
        return redirect()->to(base_url('/'))->with('messages', 'logout success');
    }

    public function viewChangePasswordFirst()
    {
        return \view('auth/changePasswordFirstTime');
    }
    public function changePasswordFirstTime()
    {
        $data = [
            'newPassword' => $this->request->getPost('newPassword', FILTER_SANITIZE_STRING),
            'confirmPassword' => $this->request->getPost('confirmPassword', FILTER_SANITIZE_STRING),
        ];
        $usersId = decrypt_id(session('usersId'));

        $validationRules = [
            'newPassword' => [
                'label' => 'newPassword',
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'new password required!',
                    'min_length' => 'new password mus be at lest 8 character long!',
                ]
            ],
            'confirmPassword' => [
                'label' => 'confirmPassword',
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'confirm password required!',
                    'min_length' => 'confirm password mus be at lest 8 character long!',
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            $error = $this->validator->getErrors();
            return redirect()->back()->with('errors', $error)->withInput();
        }

        $query = $this->Users->update($usersId, [
            'password' => password_hash($data['newPassword'], PASSWORD_BCRYPT),
            'password_updated' => date('Y-m-d'),
        ]);

        if (!$query) {
            return redirect()->back()->with('messages_error', 'Failed change password, Try again!');
        }

        session()->remove(['usersId', 'nama', 'kontak', 'role', 'logged_in']);
        return redirect()->to(base_url())->with('messages', 'Update password success, please login again!');
    }


    public function changePassword()
    {
        $data = [
            'oldPassword' => $this->request->getPost('oldPassword', FILTER_SANITIZE_STRING),
            'newPassword' => $this->request->getPost('newPassword', FILTER_SANITIZE_STRING),
            'confirmNewPassword' => $this->request->getPost('confirmNewPassword', FILTER_SANITIZE_STRING),
            'usersId' => decrypt_id(session('usersId')),
        ];

        $validationRules = [
            'oldPassword' => [
                'label' => 'oldPassword',
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Old Password is required!',
                    'min_length' => 'old password must be at least 8 character long'
                ]
            ],
            'newPassword' => [
                'label' => 'newPassword',
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'New Password is required!',
                    'min_length' => 'New password must be at least 8 character long'
                ]
            ],
            'confirmNewPassword' => [
                'label' => 'confirmNewPassword',
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Confirm New Password is required!',
                    'min_length' => 'Confirm New password must be at least 8 character long'
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            $errors = $this->validator->getErrors();
            return redirect()->back()->with('errors', $errors)->withInput();
        }

        $user = $this->Users->where('usersId', $data['usersId'])->first();
        // dd($user);
        $userPassword = $user['password'];

        if (!password_verify($data['oldPassword'], $userPassword)) {
            return redirect()->back()->with('messages_error', 'incorect old password');
        }

        if ($data['newPassword'] != $data['confirmNewPassword']) {
            return redirect()->back()->with('messages_error', 'confirm password not match');
        }

        $changePassword =  $this->Users->update(
            $data['usersId'],
            [
                'password' => password_hash($data['newPassword'], PASSWORD_BCRYPT)
            ]
        );

        if (!$changePassword) {
            return redirect()->back()->with('messages_error', 'Failed Change Password, Try again!');
        }

        session()->remove(['usersId', 'nama', 'kontak', 'role', 'logged_in', 'status']);
        return redirect()->to(base_url())->with('messages', 'Update password success, please login again!');
    }

    public function resetPassword() {}
}
