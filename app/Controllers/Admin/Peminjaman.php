<?php

namespace App\Controllers\Admin;

use Exception;
use Config\Database;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Peminjaman extends BaseController
{
    public function index()
    {
        $data = [
            'peminjaman' => $this->Peminjaman->getAll(),
            'active' => 'peminjaman'

        ];
        // return $this->response->setJSON($data);

        return view('admin/peminjaman/index', $data);
    }

    public function viewAddUser()
    {
        return view('admin/peminjaman/addUser');
    }

    public function showUserByUsername()
    {
        // dd(session('UID'), session('Uuname'), session('Uusername'));
        if (session('UID') && session('Uuname') && session('Uusername')) {
            session()->remove(['UID', 'Uname', 'Uusername']);
        }

        $data = [
            'username' => $this->request->getPost('username', FILTER_SANITIZE_STRING),
        ];

        $validationRules = [
            'username' => [
                'label' => 'username',
                'rules' => 'required|min_length[5]',
                'errors' => [
                    'required' => 'Username Required!',
                    'min_length' => 'Username mus be at least 5 character long!'
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            $errors = $this->validator->getErrors();
            return redirect()->back()->with('errors', $errors)->withInput();
        }

        $user = $this->Users->where('username', $data['username'])->first();

        if (!$user) {
            return redirect()->back()->with('messages_error', 'User not found!')->withInput();
        }
        $suspendUsername = $user['username'];
        if ($user['status'] == 'suspend') {
            return \redirect()->back()->with('messages_error', "Username  $suspendUsername on suspended ");
        }

        if (!$user) {
            return redirect()->back()->with('messages_error', 'User not found')->withInput();
        }
        // dd($user);
        self::setSession($user);

        return redirect()->to(base_url('admin/add-user-peminjaman'));
    }

    private static function setSession($user)
    {
        $data = [
            'UID' => $user['usersId'],
            'Uname' => $user['nama'],
            'Uusername' => $user['username'],
            'UnoHp' => $user['kontak']
        ];

        session()->set($data);
    }

    public function viewAddTools()
    {
        return \view('admin/peminjaman/addTools');
    }

    public function toolToCart()
    {
        $data = [
            'kodeAlat' => $this->request->getPost('kodeAlat', FILTER_SANITIZE_STRING),
        ];

        $validationRules = [
            'kodeAlat' => [
                'label' => 'kodeAlat',
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Kode Alat Required!',
                    'min_length' => 'Kode alat must be at leat 3 character long!',
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $alat = $this->MntTools->where('kodeAlat', $data['kodeAlat'])->where('status', 'tersedia')->first();

        if (!$alat) {
            return redirect()->back()->with('messages_error', 'Alat Not Found!')->withInput();
        }
        // dd($alat);

        $cart = session()->get('cart') ?? [];

        foreach ($cart as $item) {
            if ($item['kodeAlat'] == $alat['kodeAlat']) {
                return redirect()->back()->with('messages_error', 'Alat sudah ada di keranjang!')->withInput();
            }
        }



        $cart[] = [
            'kodeAlat' => $alat['kodeAlat'],
            'namaAlat' => $alat['namaAlat']
        ];

        session()->set('cart', $cart);

        return redirect()->back();
    }

    public function removeToolSession()
    {
        $kodeAlat = $this->request->getPost('sessionIndexInv');

        $cart = session()->get('cart') ?? [];

        $cart = array_filter($cart, function ($item) use ($kodeAlat) {
            return $item['kodeAlat'] !== $kodeAlat;
        });

        session()->set('cart', $cart);

        return redirect()->back();
    }

    public function cart()
    {
        return \view('admin/peminjaman/cart');
    }

    public function removeToolAndUserSession()
    {
        session()->remove(['UID', 'Uname', 'Uusername', 'cart']);
        return redirect()->to(base_url('admin/add-user-peminjaman'))->with('messages_error', 'Transaksi dibatalkan!');;
    }

    public function createPeminjaman()
    {
        $data = [
            'usersId' => $this->request->getPost('usersId'),
            'tglPinjam' => $this->request->getPost('tglPinjam', \FILTER_SANITIZE_STRING),
            'keteranganPeminjaman' => $this->request->getPost('keteranganPeminjaman', \FILTER_SANITIZE_STRING),
            'statusPeminjaman' => $this->request->getPost('statusPeminjaman', \FILTER_SANITIZE_STRING),
        ];

        $validationRules = [
            'usersId' => [
                'label' => 'usersId',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Input User First'
                ]
            ],
            'tglPinjam' => [
                'label' => 'tglPinjam',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tgl Peminjaman Required'
                ]
            ],
            'keteranganPeminjaman' => [
                'label' => 'keteranganPeminjaman',
                'rules' => 'min_length[0]',
                'errors' => [
                    'min_length' => 'Keterangan must be at leat 0 character long!',
                ]
            ],
            'statusPeminjaman' => [
                'label' => 'statusPeminjaman',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Status Peminjaman Not Valid!'
                ]
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $cart = session()->get('cart') ?? [];
        // return $this->response->setJSON($cart);

        if (empty($cart)) {
            return redirect()->back()->with('messages_error', 'Cart is Empty, Please add some tool');
        }

        $db = Database::connect();
        $db->transStart();

        try {
            $today  = date('Y-m-d');
            $prefix = 'PNJ';
            $datePart = date('Ymd');

            $lastTransaction = $this->Peminjaman
                ->where('tanggalPinjam', $today)
                ->orderBy('peminjamanCode', 'DESC')
                ->first();

            $lastIndex = 0;
            if ($lastTransaction) {
                $lastCode = $lastTransaction['peminjamanCode'];
                $lastIndex = intval(substr($lastCode, -3));
            }

            $newIndex = str_pad($lastIndex + 1, 3, '0', STR_PAD_LEFT);
            $peminjamanCode = $prefix . $datePart . $newIndex;

            $peminjamanData = [
                'peminjamanCode' => $peminjamanCode,
                'usersId' => $data['usersId'],
                'tanggalPinjam' => $data['tglPinjam'],
                'tanggalKembali' => $data['tglPinjam'],
                'byAdmin' => decrypt_id(session('usersId')),
                'keteranganPeminjaman' => $data['keteranganPeminjaman'],
                'statusPeminjaman' => $data['statusPeminjaman'],
            ];

            $this->Peminjaman->insert($peminjamanData);
            $this->Users->update($peminjamanData['usersId'], ['status' => 'suspend']);

            if ($this->Peminjaman->db->affectedRows() <= 0) {
                $db->transRollback();
                return redirect()->back()->with('messages_error', 'Failed Create Peminjaman');
            }


            foreach ($cart as $item) {
                $this->DetailPeminjaman->insert([
                    'peminjamanCode' => $peminjamanCode,
                    'kodeAlat' => $item['kodeAlat']
                ]);

                $this->MntTools->where('kodeAlat', $item['kodeAlat'])->set('status', 'dipinjam')->update();
            }

            $db->transComplete();
            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->back()->with('messages_error', 'Failed Create New Transaction!');
            }

            session()->remove(['UID', 'Uname', 'Uusername', 'cart']);
            return redirect()->to(base_url('admin/peminjaman'))->with('messages', 'Transaction Success!');
        } catch (Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('messages_error', 'Failed Create New Transaction!');
        }
    }
}
