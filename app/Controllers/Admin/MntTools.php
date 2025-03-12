<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
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
            return redirect()->back()->with('messages_error', 'Gagal menambahkan data inventori baru!')->withInput();
        }

        return redirect()->back()->with('messages', 'Berhasil menambahkan data inventori baru dengan kode: ' . $kodeAlat);
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

        if ($dataUpdate['status'] == 'perawatan') {
            if ($dataUpdate['status'] == 'perawatan') {
                $dataPerawatan = [
                    'mntToolsId' => $mntToolsId,
                    'tanggalPerawatan' => date('Y-m-d'),
                    'statusPerawatan' => 'on progres',
                ];
                $query = $this->MntTools->update($mntToolsId, $dataUpdate);
                $queryPerawatan = $this->Perawatan->insert($dataPerawatan);
                $kodeAlt = $dataUpdate['kodeAlat'];
                if (!$queryPerawatan && !$query) {
                    return redirect()->to(base_url('admin/tools'))->with('messages_error', "Failed entered item code $kodeAlt into the maintenance process");
                }
                return redirect()->to(base_url('admin/tools'))->with('messages', "Item code $kodeAlt is entered into the maintenance process");
            }
        }
        if ($dataUpdate['status'] == 'perbaikan') {
            if ($dataUpdate['status'] == 'perbaikan') {
                $dataPerbaikan = [
                    'mntToolsId' => $mntToolsId,
                    'tanggalPerbaikan' => date('Y-m-d'),
                    'statusPerbaikan' => 'on progres',
                ];
                $query = $this->MntTools->update($mntToolsId, $dataUpdate);
                $queryPerbaikan = $this->Perbaikan->insert($dataPerbaikan);
                $kodeAlt = $dataUpdate['kodeAlat'];
                if (!$queryPerbaikan && !$query) {
                    return redirect()->to(base_url('admin/tools'))->with('messages_error', "Failed entered item code $kodeAlt into the maintenance process");
                }
                return redirect()->to(base_url('admin/tools'))->with('messages', "Item code $kodeAlt is entered into the maintenance process");
            }
        }

        $query = $this->MntTools->update($mntToolsId, $dataUpdate);

        if (!$query) {
            return redirect()->back()->with('messages_error', 'Failed to update inventory')->withInput();
        }

        return redirect()->to(base_url('admin/tools'))->with('messages', 'Update data Success!');
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
        $tools = $this->MntTools->findAll();

        $html = '<table style="width:80px; text-align:center; border-spacing:8px;"><tr>';

        $count = 0;
        foreach ($tools as $tool) {
            $qrCode = new QrCode("{$tool['kodeAlat']}");
            $qrCode->setSize(80);
            $qrCode->setMargin(5);

            $writer = new PngWriter();
            $qrImage = $writer->write($qrCode)->getDataUri();

            $html .= '<td style="border:1px solid #ddd; padding:8px;">';
            $html .= "<img src='{$qrImage}' style='width:80px; height:80px;' /><br>";
            $html .= "<strong style='font-size:10px;'>{$tool['kodeAlat']}</strong><br>";
            $html .= "<span style='font-size:9px;'>{$tool['namaAlat']}</span>";
            $html .= '</td>';

            $count++;

            if ($count % 6 == 0) {
                $html .= '</tr><tr>';
            }
        }

        $html .= '</tr></table>';

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream('mntToolsQr.pdf', ['Attachment' => false]);
    }
    public function cetakSingleQR()
    {
        $kode = $this->request->getPost('kodeAlat');
        $tool = $this->MntTools->where('kodeAlat', $kode)->first();
        // \dd($tool);

        $html = '<table style="width:80px; text-align:center; border-spacing:8px;"><tr>';

        $qrCode = new QrCode("{$tool['kodeAlat']}");
        $qrCode->setSize(80);
        $qrCode->setMargin(5);

        $writer = new PngWriter();
        $qrImage = $writer->write($qrCode)->getDataUri();

        $html .= '<td style="border:1px solid #ddd; padding:8px;">';
        $html .= "<img src='{$qrImage}' style='width:80px; height:80px;' /><br>";
        $html .= "<strong style='font-size:10px;'>{$tool['kodeAlat']}</strong><br>";
        $html .= "<span style='font-size:9px;'>{$tool['namaAlat']}</span>";
        $html .= '</td>';



        $html .= '</tr></table>';

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream('mntToolsQr.pdf', ['Attachment' => false]);
    }
}
