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
            'active' => 'data-inventaris'

        ];

        // return $this->response->setJSON($data);


        return view('admin/inventaris/index', $data);
    }

    public function store()
    {
        $data = [
            'categoryId' => $this->request->getPost('categoryId', FILTER_SANITIZE_NUMBER_INT),
            'jumlahDI'   => $this->request->getPost('jumlahDI'),
            'vendor'     => $this->request->getPost('vendor', FILTER_SANITIZE_STRING),
            'harga'      => $this->request->getPost('harga'),
            'total'      => $this->request->getPost('total'),
            'namaAlat'      => $this->request->getPost('namaAlat'),
        ];

        $validationRules = [
            'categoryId' => [
                'label' => 'Kategori',
                'rules' => 'required|is_natural_no_zero',
                'errors' => ['required' => 'Kategori wajib diisi']
            ],
            'jumlahDI' => [
                'label' => 'Jumlah',
                'rules' => 'required|is_natural_no_zero',
                'errors' => ['required' => 'Jumlah wajib diisi']
            ],
            'vendor' => [
                'label' => 'Vendor',
                'rules' => 'required',
                'errors' => ['required' => 'Vendor wajib diisi']
            ],
            'namaAlat' => [
                'label' => 'namaAlat',
                'rules' => 'required',
                'errors' => ['required' => 'Nama Alat wajib diisi']
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        if (empty($data['harga']) && empty($data['total'])) {
            return redirect()->back()->with('messages_error', 'Isikan salah satu harga atau total')->withInput();
        }

        if (empty($data['total'])) {
            $data['total'] = $data['harga'] * $data['jumlahDI'];
        }

        if (empty($data['harga'])) {
            $data['harga'] = $data['total'] / $data['jumlahDI'];
        }

        $dataQuery = [
            'categoryId' => $data['categoryId'],
            'tanggalDI'  => date('Y-m-d'),
            'jumlahDI'   => $data['jumlahDI'],
            'vendor'     => $data['vendor'],
            'harga'      => $data['harga'],
            'total'      => $data['total'],
        ];

        $dataKategori = $this->Categories->where('categoryId', $data['categoryId'])->first();
        $jumlahByKategori = $this->MntTools->where('categoryId', $data['categoryId'])->countAllResults();
        $batasKategori = (int) $dataKategori['jumlah'];
        $namaKategori = $dataKategori['namaKategori'];

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $db->table('datainventaris')->insert($dataQuery);

            $jumlahPerKategori = $this->DataInventaris->countJumlah($data['categoryId']);
            $this->Categories->update($data['categoryId'], ['jumlah' => $jumlahPerKategori]);

            $words = explode(' ', $namaKategori);
            $prefix = count($words) >= 3
                ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1) . substr($words[2], 0, 1))
                : (count($words) == 2
                    ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
                    : strtoupper(substr($words[0], 0, 2)));
            $prefix .= $data['categoryId'];

            $existingCodes = $this->MntTools->select('kodeAlat')->like('kodeAlat', $prefix, 'after')->findAll();
            $usedNumbers = array_map(function ($item) {
                return (int) substr($item['kodeAlat'], -3);
            }, $existingCodes);

            $jumlah = $data['jumlahDI'];
            $nextNumber = 1;

            for ($i = 1; $i <= $jumlah; $i++) {
                while (in_array($nextNumber, $usedNumbers)) {
                    $nextNumber++;
                }

                $kodeAlat = sprintf('%s%03d', $prefix, $nextNumber);
                $usedNumbers[] = $nextNumber;

                $toolData = [
                    'namaAlat'   => $data['namaAlat'],
                    'kodeAlat'   => $kodeAlat,
                    'kondisi'    => 'Baik',
                    'status'     => 'tersedia',
                    'categoryId' => $data['categoryId'],
                ];

                $db->table('mnttools')->insert($toolData);
            }

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal menyimpan data!');
            }

            $db->transCommit();
            return redirect()->to(base_url('admin/inventaris'))->with('messages', 'Insert Data berhasil!');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('messages_error', $e->getMessage())->withInput();
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
            'categories' => $this->Categories->findAll(),
            'active' => 'data-inventaris'
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
