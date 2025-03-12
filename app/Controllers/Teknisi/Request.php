<?php

namespace App\Controllers\Teknisi;

use Config\Database;
use App\Controllers\BaseController;
use App\Models\DetailPermintaan;
use CodeIgniter\HTTP\ResponseInterface;

class Request extends BaseController
{
    public function index()
    {
        $data = [
            'requests' => $this->Requests->getDataByUser(decrypt_id(session('usersId'))),
        ];

        return \view('teknisi/request/index', $data);
    }

    public function viewRequest()
    {
        $categories = $this->Categories->findAll();
        $datacategory = [];

        foreach ($categories as $kt) {
            $jumlahAlatTersedia = $this->MntTools
                ->where('status', 'tersedia')
                ->where('categoryId', $kt['categoryId'])
                ->countAllResults();

            $datacategory[] = [
                'categoryId' => $kt['categoryId'],
                'namaKategori' => $kt['namaKategori'],
                'jumlahTersedia' => $jumlahAlatTersedia,
            ];
        }

        $data = [
            'categories' => $datacategory,
        ];

        return view('teknisi/request/newRequest', $data);
    }

    public function request()
    {
        $cartDataJson = $this->request->getPost('cart_data');
        $cartData = json_decode($cartDataJson, true);

        if (!is_array($cartData) || empty($cartData)) {
            return redirect()->to('/teknisi/request/new')->with('messages_error', 'Data keranjang kosong atau tidak valid.');
        }


        $today = date('Y-m-d');
        $prefix = 'RQT';
        $datePart = date('Ymd');

        $lastRequest = $this->Requests
            ->where('tanggalPermintaan', $today)
            ->orderBy('permintaanCode', 'DESC')
            ->first();

        $lastIndex = 0;
        if ($lastRequest) {
            $lastCode = $lastRequest['permintaanCode'];
            $lastIndex = intval(substr($lastCode, -3));
        }

        $newIndex = str_pad($lastIndex + 1, 3, '0', STR_PAD_LEFT);
        $permintaanCode = $prefix . $datePart . $newIndex;

        $usersId = decrypt_id(session('usersId'));

        $permintaanData = [
            'permintaanCode' => $permintaanCode,
            'usersId' => $usersId,
            'status' => 'pending',
            'tanggalPermintaan' => $today
        ];

        $this->Requests->insert($permintaanData);

        if ($this->Requests->db->affectedRows() <= 0) {
            return redirect()->back()->with('messages_error', 'Failed Create Request! ');
        }

        $cekRequest = $this->Requests->where('permintaanCode', $permintaanCode)->first();
        if (!$cekRequest) {
            return redirect()->back()->with('messages_error', 'Request not found, cannot insert details.');
        }

        $detailPermintaanData = [];
        foreach ($cartData as $item) {
            $detailPermintaanData[] = [
                'permintaanCode' => $permintaanCode,
                'categoryId' => $item['id']
            ];
        }

        $this->DetailPermintaan->insertBatch($detailPermintaanData);

        if ($this->DetailPermintaan->db->affectedRows() <= 0) {
            return redirect()->back()->with('messages_error', 'Failed Create Request Details! ');
        }


        return redirect()->to(base_url('teknisi/request'))->with('messages', 'Request berhasil disimpan.');
    }
}
