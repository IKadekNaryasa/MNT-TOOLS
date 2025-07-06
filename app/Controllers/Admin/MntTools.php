<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;
use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class MntTools extends BaseController
{
    public function index()
    {
        $data = [
            'tools' => $this->MntTools->getAll(),
            'categories' => $this->Categories->findAll(),
            'active' => 'mnt-tools'

        ];

        return view('admin/tools/index', $data);
    }

    public function store()
    {
        $data = [
            'namaAlat'   => $this->request->getPost('namaAlat', FILTER_SANITIZE_STRING),
            'kondisi'    => $this->request->getPost('kondisi', FILTER_SANITIZE_STRING),
            'status'     => $this->request->getPost('status', FILTER_SANITIZE_STRING),
            'categoryId' => $this->request->getPost('categoryId', FILTER_SANITIZE_NUMBER_INT),
        ];

        $dataKategori = $this->Categories->where('categoryId', $data['categoryId'])->first();

        if (!$dataKategori) {
            return redirect()->back()->with('messages_error', 'Kategori tidak ditemukan!')->withInput();
        }

        $jumlahByKategori = $this->MntTools->where('categoryId', $data['categoryId'])->countAllResults();
        $jumlahInKategori = (int) $dataKategori['jumlah'];
        $namaKategori = $dataKategori['namaKategori'];

        if ($jumlahByKategori >= $jumlahInKategori) {
            return redirect()->back()->with('messages_error', 'Jumlah Tools sudah mencapai batas maksimum untuk kategori ini!')->withInput();
        }

        $words = explode(' ', $namaKategori);
        $prefix =
            count($words) >= 3 ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1) . substr($words[2], 0, 1)) : (count($words) > 1
                ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
                : strtoupper(substr($words[0], 0, 2)));

        $prefix .= $data['categoryId'];

        $existingCodes = $this->MntTools->select('kodeAlat')
            ->like('kodeAlat', $prefix, 'after')
            ->orderBy('kodeAlat', 'ASC')
            ->findAll();

        $usedNumbers = [];
        foreach ($existingCodes as $code) {
            $usedNumbers[] = (int) substr($code['kodeAlat'], -3);
        }

        $nextNumber = 1;
        while (in_array($nextNumber, $usedNumbers)) {
            $nextNumber++;
        }

        $kodeAlat = sprintf('%s%03d', $prefix, $nextNumber);

        $dataInsert = [
            'namaAlat'   => $data['namaAlat'],
            'kodeAlat'   => $kodeAlat,
            'kondisi'    => $data['kondisi'],
            'status'     => $data['status'],
            'categoryId' => $data['categoryId'],
        ];

        if (!$this->MntTools->insert($dataInsert)) {
            return redirect()->back()->with('messages_error', 'Gagal menambahkan data  baru!')->withInput();
        }

        return redirect()->back()->with('messages', 'Berhasil menambahkan data  baru dengan kode: ' . $kodeAlat);
    }


    public function viewUpdate($mntToolsId)
    {
        $tools = $this->MntTools->getDataByToolId(decrypt_id($mntToolsId));
        $isEditable = $tools[0]['status'] == 'tersedia';

        if ($isEditable != 'tersedia') {
            return \redirect()->back()->with('messages_error', 'Tools hanya bisa di edit jika status tersedia');
        }

        $data = [
            'tools' => $tools,
            'categories' => $this->Categories->findAll(),
            'isEditable' => $isEditable,
            'active' => 'mnt-tools'
        ];
        // return $this->response->setJSON($data);
        return view('admin/tools/toolUpdate', $data);
    }

    public function update()
    {
        $mntToolsId_encrypted =  $this->request->getPost('mntToolsId', FILTER_SANITIZE_STRING);
        $mntToolsId = decrypt_id($mntToolsId_encrypted);
        $data = [
            'namaAlat' => $this->request->getPost('namaAlat', FILTER_SANITIZE_STRING),
            'kodeAlat' => $this->request->getPost('kodeAlat', FILTER_SANITIZE_STRING),
            'kondisi' => $this->request->getPost('kondisi', FILTER_SANITIZE_STRING),
            'status' => $this->request->getPost('status', FILTER_SANITIZE_STRING),
            'categoryId' => $this->request->getPost('categoryId', FILTER_SANITIZE_NUMBER_INT),
        ];
        $updated_at = date('Y-m-d');

        $validationRules = [
            'namaAlat' => [
                'label' => 'namaAlat',
                'rules' => 'required',
                'errors' => [
                    'required' => 'nama alat required!',
                ]
            ],
            'kodeAlat' => [
                'label' => 'kodeAlat',
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'kode alat required!',
                    'min_length' => 'Kode alat must be at least 3 character long'
                ]
            ],
            'kondisi' => [
                'label' => 'kondisi',
                'rules' => 'required',
                'errors' => [
                    'required' => 'kondisi required!',
                ]
            ],
            'status' => [
                'label' => 'status',
                'rules' => 'required',
                'errors' => [
                    'required' => 'status required!',
                ]
            ],
            'categoryId' => [
                'label' => 'categoryId',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kategori required!',
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            $error = $this->validator->getErrors();
            return redirect()->back()->with('errors', $error)->withInput();
        }

        $exists = $this->MntTools->where('kodeAlat', $data['kodeAlat'])->where('mntToolsId !=', $mntToolsId)->first();

        if ($exists) {
            return redirect()->back()->with('messages_error', 'Kode Alat already exists')->withInput();
        }

        $dataUpdate = [
            'namaAlat' => $data['namaAlat'],
            'kodeAlat' => $data['kodeAlat'],
            'kondisi' => $data['kondisi'],
            'status' => $data['status'],
            'categoryId' => $data['categoryId'],
        ];

        $db = Database::connect();
        try {
            $db->transBegin();

            if ($dataUpdate['status'] == 'perawatan') {
                $dataPerawatan = [
                    'mntToolsId' => $mntToolsId,
                    'tanggalPerawatan' => date('Y-m-d'),
                    'statusPerawatan' => 'on progres',
                ];
                $this->Perawatan->insert($dataPerawatan);
            }

            if ($dataUpdate['status'] == 'perbaikan') {
                $dataPerbaikan = [
                    'mntToolsId' => $mntToolsId,
                    'tanggalPerbaikan' => date('Y-m-d'),
                    'statusPerbaikan' => 'on progres',
                ];
                $this->Perbaikan->insert($dataPerbaikan);
            }

            $this->MntTools->update($mntToolsId, $dataUpdate);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->back()->with('messages_error', 'Failed to update inventory')->withInput();
            }
            $db->transCommit();
            return redirect()->to(base_url('admin/tools'))->with('messages', 'Update data Success!');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('messages_error', 'Failed to update inventory')->withInput();
        }
    }

    public function delete()
    {
        $encrypted_mntToolsId = $this->request->getPost('mntToolsId', FILTER_SANITIZE_STRING);
        $mntToolsId = decrypt_id($encrypted_mntToolsId);

        $validationRules = [
            'mntToolsId' => [
                'label' => 'mntToolsId',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tools not valid!'
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            $error = $this->validator->getErrors();
            return redirect()->to(base_url('admin/tools'))->with('errors', $error);
        }

        $toolsAvailableToDelete = $this->MntTools->where('mntToolsId', $mntToolsId)->where('status', 'rusak')->first();

        if (!$toolsAvailableToDelete) {
            return redirect()->back()->with('messages_error', 'Tool Not Available To Deleted');
        }

        $query = $this->MntTools->delete($mntToolsId);

        if (!$query) {
            return redirect()->to(base_url('admin/tools'))->with('messages_error', 'Failed to delete!');
        }

        return redirect()->to(base_url('admin/tools'))->with('messages', 'Success delete data!');
    }

    public function cetakQR()
    {
        $selected = $this->request->getPost('selected_tools');

        $tools = $selected
            ? $this->MntTools->whereIn('mntToolsId', $selected)->findAll()
            : $this->MntTools->findAll();

        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=QRCodeList.html");

        echo '<html><head><title>QR Code List</title><style>
        table { width:100%; border-spacing:10px; }
        td { text-align:center; padding:10px; border:1px solid #ddd; }
        img { width:80px; height:80px; }
        small { font-size:10px; display:block; }
    </style></head><body>';

        echo '<table><tr>';
        $count = 0;

        foreach ($tools as $tool) {
            $qrCode = new \Endroid\QrCode\QrCode($tool['kodeAlat']);
            $qrCode->setSize(80)->setMargin(5);

            $writer = new \Endroid\QrCode\Writer\PngWriter();
            $qrImage = $writer->write($qrCode)->getDataUri();

            echo '<td>';
            echo "<img src='{$qrImage}'><br>";
            echo "<strong>{$tool['kodeAlat']}</strong><br>";
            echo "<small>{$tool['namaAlat']}</small>";
            echo '</td>';

            $count++;
            if ($count % 4 == 0) echo '</tr><tr>';
        }

        echo '</tr></table>';
        echo '</body></html>';
    }



    public function cetakSingleQR()
    {
        $kode = $this->request->getPost('kodeAlat');
        $tool = $this->MntTools->where('kodeAlat', $kode)->first();

        if (!$tool) {
            return 'Data alat tidak ditemukan.';
        }

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=SingleQRCode.html");

        echo '<html><head><title>Cetak QR Code</title>';
        echo '<style>
                @page { size: A4; margin: 20mm; } /* Atur ukuran kertas A4 */
                body { font-family: Arial, sans-serif; text-align: center; }
                .container { width: 210mm; height: 297mm; display: flex; align-items: center; justify-content: center; flex-direction: column; }
                table { width: 80px; border-spacing: 8px; text-align: center; margin: auto; }
                td { border: 1px solid #ddd; padding: 8px; }
                img { width: 80px; height: 80px; }
                .kode { font-size: 10px; font-weight: bold; }
                .nama { font-size: 9px; }
              </style>';
        echo '</head><body>';
        echo '<div class="container">';

        $qrCode = new QrCode($tool['kodeAlat']);
        $qrCode->setSize(80);
        $qrCode->setMargin(5);

        $writer = new PngWriter();
        $qrImage = $writer->write($qrCode)->getDataUri();

        echo '<table><tr>';
        echo '<td>';
        echo "<img src='{$qrImage}' /><br>";
        echo "<span class='kode'>{$tool['kodeAlat']}</span><br>";
        echo "<span class='nama'>{$tool['namaAlat']}</span>";
        echo '</td>';
        echo '</tr></table>';

        echo '</div>';
        echo '</body></html>';
    }

    public function cekStok()
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
            'active' => 'mnt-tools'
        ];
        return view('admin/tools/stok', $data);
    }

    public function cekBanding()
    {
        $categoryList = $this->Categories->findAll();
        $pesanKurang = [];

        foreach ($categoryList as $kategori) {
            $jumlahSeharusnya = $kategori['jumlah'];
            $jumlahAktual = $this->MntTools->where('categoryId', $kategori['categoryId'])->countAllResults();

            if ($jumlahAktual < $jumlahSeharusnya) {
                $kurang = $jumlahSeharusnya - $jumlahAktual;
                $pesanKurang[] = "[*] " . $kategori['namaKategori'] . " masih kurang " . $kurang . " buah!";
            }
        }

        if (empty($pesanKurang)) {
            return redirect()->back()->with('matches', 'Jumlah alat sesuai dengan jumlah pada Kategori!');
        } else {
            $pesan = implode('<br>', $pesanKurang);
            return redirect()->back()->with('notMatches', $pesan);
        }
    }
}
