<?php

namespace App\Controllers\Export;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class GlobalExport extends BaseController
{
    public function inventory()
    {
        $randomIndex = bin2hex(random_bytes(5));
        $data = [
            'kategori' => $this->request->getPost('kategori', FILTER_SANITIZE_NUMBER_INT),
            'tanggalAwal' => $this->request->getPost('tanggalAwal'),
            'tanggalAkhir' => $this->request->getPost('tanggalAkhir'),
        ];

        $validationRules = [
            'kategori' => [
                'label' => 'kategori',
                'rules' => 'required',
                'errors' => [
                    'required' => 'kategori required!'
                ]
            ],
            'tanggalAwal' => [
                'label' => 'tanggalAwal',
                'rules' => 'required',
                'errors' => [
                    'required' => 'tanggal Awal required!'
                ]
            ],
            'tanggalAkhir' => [
                'label' => 'tanggalAkhir',
                'rules' => 'required',
                'errors' => [
                    'required' => 'tanggal Akhir required!'
                ]
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $inventories = [];
        if ($data['kategori'] == '00') {
            $inventories = $this->DataInventaris->getAllDataByRange($data['tanggalAwal'], $data['tanggalAkhir']);
        } else {
            $inventories = $this->DataInventaris->getAllDataByRangeAndCategory($data['tanggalAwal'], $data['tanggalAkhir'], $data['kategori']);
        }
        // return $this->response->setJSON($inventories);
        $awal = $data['tanggalAwal'];
        $akhir = $data['tanggalAkhir'];
        if (!$inventories) {
            return redirect()->back()->with('messages_error', "Data Inventaris dari tanggal $awal - $akhir Tidak ada");
        }

        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Kategori')
            ->setCellValue('C1', 'Tanggal Barang Masuk')
            ->setCellValue('D1', 'Vendor')
            ->setCellValue('E1', 'Jumlah')
            ->setCellValue('F1', 'Total Harga (Rp)');

        $row = 2;
        $no = 1;

        foreach ($inventories as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item['namaKategori']);
            $sheet->setCellValue('C' . $row, $item['tanggalDI']);
            $sheet->setCellValue('D' . $row, $item['vendor']);
            $sheet->setCellValue('E' . $row, $item['jumlahDI']);
            $sheet->setCellValue('F' . $row, $item['total']);
            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('"Rp" #,##0');
            $row++;
        }

        $writer = new Xlsx($spreadSheet);
        $fileName = $randomIndex . '_Data_Inventaris_' . date('Y-m-d') . '_' . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
    public function peminjaman()
    {
        $randomIndex = bin2hex(random_bytes(5));
        $data = [
            'tanggalAwal' => $this->request->getPost('tanggalAwal'),
            'tanggalAkhir' => $this->request->getPost('tanggalAkhir'),
        ];

        $validationRules = [
            'tanggalAwal' => [
                'label' => 'tanggalAwal',
                'rules' => 'required',
                'errors' => [
                    'required' => 'tanggal Awal required!'
                ]
            ],
            'tanggalAkhir' => [
                'label' => 'tanggalAkhir',
                'rules' => 'required',
                'errors' => [
                    'required' => 'tanggal Akhir required!'
                ]
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        // return $this->response->setJSON($data);
        $peminjaman = $this->Peminjaman->getAllDataByRange($data['tanggalAwal'], $data['tanggalAkhir']);

        $awal = $data['tanggalAwal'];
        $akhir = $data['tanggalAkhir'];
        if (!$peminjaman) {
            return redirect()->back()->with('messages_error', "Data Peminjaman dari tanggal $awal - $akhir Tidak ada");
        }

        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Peminjam')
            ->setCellValue('C1', 'Kontak')
            ->setCellValue('D1', 'Kode Peminjaman')
            ->setCellValue('E1', 'Tanggal')
            ->setCellValue('F1', 'Status');

        $row = 2;
        $no = 1;

        foreach ($peminjaman as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item['nama']);
            $sheet->setCellValue('C' . $row, $item['kontak']);
            $sheet->setCellValue('D' . $row, $item['peminjamanCode']);
            $sheet->setCellValue('E' . $row, $item['tanggalPinjam']);
            $sheet->setCellValue('F' . $row, $item['statusPeminjaman']);
            $row++;
        }

        $writer = new Xlsx($spreadSheet);
        $fileName = $randomIndex . '_Data_Peminjaman_' . date('Y-m-d') . '_' . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
    public function pengembalian()
    {
        $randomIndex = bin2hex(random_bytes(5));

        $data = [
            'tanggalAwal' => $this->request->getPost('tanggalAwal'),
            'tanggalAkhir' => $this->request->getPost('tanggalAkhir'),
        ];

        $validationRules = [
            'tanggalAwal' => [
                'label' => 'tanggalAwal',
                'rules' => 'required',
                'errors' => [
                    'required' => 'tanggal Awal required!'
                ]
            ],
            'tanggalAkhir' => [
                'label' => 'tanggalAkhir',
                'rules' => 'required',
                'errors' => [
                    'required' => 'tanggal Akhir required!'
                ]
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $pengembalian = $this->Pengembalian->getAllDataByRange($data['tanggalAwal'], $data['tanggalAkhir']);

        $awal = $data['tanggalAwal'];
        $akhir = $data['tanggalAkhir'];
        if (!$pengembalian) {
            return redirect()->back()->with('messages_error', "Data Pengembalian dari tanggal $awal - $akhir Tidak ada");
        }
        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Peminjam')
            ->setCellValue('C1', 'Kontak')
            ->setCellValue('D1', 'Kode Peminjaman')
            ->setCellValue('E1', 'Tanggal Kembali')
            ->setCellValue('F1', 'Status');

        $row = 2;
        $no = 1;

        foreach ($pengembalian as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item['nama']);
            $sheet->setCellValue('C' . $row, $item['kontak']);
            $sheet->setCellValue('D' . $row, $item['peminjamanCode']);
            $sheet->setCellValue('E' . $row, $item['tanggalKembali']);
            $sheet->setCellValue('F' . $row, $item['statusPengembalian']);
            $row++;
        }

        $writer = new Xlsx($spreadSheet);
        $fileName = $randomIndex . '_Data_Pengembalian_' . date('Y-m-d') . '_' . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
    public function perbaikan()
    {
        $randomIndex = bin2hex(random_bytes(5));

        $data = [
            'tanggalAwal' => $this->request->getPost('tanggalAwal'),
            'tanggalAkhir' => $this->request->getPost('tanggalAkhir'),
        ];

        $validationRules = [
            'tanggalAwal' => [
                'label' => 'tanggalAwal',
                'rules' => 'required',
                'errors' => [
                    'required' => 'tanggal Awal required!'
                ]
            ],
            'tanggalAkhir' => [
                'label' => 'tanggalAkhir',
                'rules' => 'required',
                'errors' => [
                    'required' => 'tanggal Akhir required!'
                ]
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }
        // return $this->response->setJSON($data);

        $perbaikan = $this->Perbaikan->getAllDataByRange($data['tanggalAwal'], $data['tanggalAkhir']);
        $awal = $data['tanggalAwal'];
        $akhir = $data['tanggalAkhir'];
        if (!$perbaikan) {
            return redirect()->back()->with('messages_error', "Data Perbaikan dari tanggal $awal - $akhir Tidak ada");
        }
        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Kode Alat')
            ->setCellValue('C1', 'Tanggal  Perbaikan')
            ->setCellValue('D1', 'Tanggal  Selesai')
            ->setCellValue('E1', 'Status')
            ->setCellValue('F1', 'Biaya');

        $row = 2;
        $no = 1;

        foreach ($perbaikan as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item['kodeAlat']);
            $sheet->setCellValue('C' . $row, $item['tanggalPerbaikan']);
            $sheet->setCellValue('D' . $row, $item['tanggalSelesai']);
            $sheet->setCellValue('E' . $row, $item['statusPerbaikan']);
            $sheet->setCellValue('F' . $row, $item['biaya']);
            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('"Rp" #,##0');
            $row++;
        }

        $writer = new Xlsx($spreadSheet);
        $fileName = $randomIndex . '_Data_Perbaikan_' . date('Y-m-d') . '_' . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
    public function perawatan()
    {
        $randomIndex = bin2hex(random_bytes(5));

        $data = [
            'tanggalAwal' => $this->request->getPost('tanggalAwal'),
            'tanggalAkhir' => $this->request->getPost('tanggalAkhir'),
        ];

        $validationRules = [
            'tanggalAwal' => [
                'label' => 'tanggalAwal',
                'rules' => 'required',
                'errors' => [
                    'required' => 'tanggal Awal required!'
                ]
            ],
            'tanggalAkhir' => [
                'label' => 'tanggalAkhir',
                'rules' => 'required',
                'errors' => [
                    'required' => 'tanggal Akhir required!'
                ]
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }
        // return $this->response->setJSON($data);

        $perawatan = $this->Perawatan->getAllDataByRange($data['tanggalAwal'], $data['tanggalAkhir']);

        $awal = $data['tanggalAwal'];
        $akhir = $data['tanggalAkhir'];
        if (!$perawatan) {
            return redirect()->back()->with('messages_error', "Data Perawatan dari tanggal $awal - $akhir Tidak ada");
        }
        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Kode Alat')
            ->setCellValue('C1', 'Tanggal  Perawatan')
            ->setCellValue('D1', 'Tanggal  Selesai')
            ->setCellValue('E1', 'Status')
            ->setCellValue('F1', 'Biaya');

        $row = 2;
        $no = 1;

        foreach ($perawatan as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item['kodeAlat']);
            $sheet->setCellValue('C' . $row, $item['tanggalPerawatan']);
            $sheet->setCellValue('D' . $row, $item['tanggalSelesai']);
            $sheet->setCellValue('E' . $row, $item['statusPerawatan']);
            $sheet->setCellValue('F' . $row, $item['biaya']);
            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('"Rp" #,##0');
            $row++;
        }

        $writer = new Xlsx($spreadSheet);
        $fileName = $randomIndex . '_Data_Perawatan_' . date('Y-m-d') . '_' . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
