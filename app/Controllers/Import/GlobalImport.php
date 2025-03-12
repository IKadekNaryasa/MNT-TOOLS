<?php

namespace App\Controllers\Import;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GlobalImport extends BaseController
{
    public function categories()
    {
        $file = $this->request->getFile('fileCategory');

        if ($file->isValid() && !$file->hasMoved()) {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $data = [];

            foreach ($sheet->getRowIterator(2) as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }
                if (!empty(trim($rowData[0])) && ($rowData[1] !== null)) {
                    $data[] = [
                        'namaKategori' => $rowData[0],
                        'jumlah' => $rowData[1],
                    ];
                }
            }


            if (!empty($data)) {
                $this->Categories->insertBatch($data);
                return redirect()->back()->with('messages', 'Import Data Success!');
            }
        }

        return redirect()->back()->with('messages_error', 'Import Data Failed!');
    }
    public function inventories()
    {
        $file = $this->request->getFile('inventoriesFile');

        if ($file->isValid() && !$file->hasMoved()) {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $data = [];

            foreach ($sheet->getRowIterator(2) as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }
                if (!empty(trim($rowData[0])) && !empty(trim($rowData[1])) && isset($rowData[2]) && !empty(trim($rowData[3])) && isset($rowData[4])) {
                    $data[] = [
                        'categoryId' => $rowData[0],
                        'tanggalDI'  => date('Y-m-d'),
                        'jumlahDI'   => $rowData[1],
                        'vendor'     => $rowData[2],
                        'keterangan' => $rowData[3] ?? '',
                        'total'      => $rowData[4]
                    ];
                }
            }

            if (!empty($data)) {
                $db = Database::connect();
                $db->transStart();

                $this->DataInventaris->insertBatch($data);
                if ($this->DataInventaris->db->affectedRows() <= 0) {
                    $db->transRollback();
                    return redirect()->back()->with('messages_error', 'Failed Import Data!');
                }

                $categoryIds = array_column($data, 'categoryId');

                foreach ($categoryIds as $categoryId) {
                    $amountByCategory = $this->DataInventaris->selectSum('jumlahDI')->where('categoryId', $categoryId)->first();

                    if ($amountByCategory) {
                        $this->Categories->update($categoryId, ['jumlah' => $amountByCategory['jumlahDI']]);

                        if ($this->Categories->db->affectedRows() <= 0) {
                            $db->transRollback();
                            return redirect()->back()->with('messages_error', 'Failed Set Jumlah on Category!');
                        }
                    }
                }
                $db->transComplete();
                return \redirect()->back()->with('messages', 'Success Import Data!');
            }
        }

        return redirect()->back()->with('error', 'Failed Import file!');
    }
    public function mntTools()
    {
        $file = $this->request->getFile('mntToolsFile');

        if ($file->isValid() && !$file->hasMoved()) {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();

            foreach ($sheet->getRowIterator(2) as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $rowData = [];

                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }

                if (!empty(trim($rowData[0])) && !empty(trim($rowData[1])) && !empty(trim($rowData[2])) && !empty(trim($rowData[3]))) {
                    $categoryId = (int) $rowData[3];
                    $dataKategori = $this->Categories->where('categoryId', $categoryId)->first();

                    if (!$dataKategori) {
                        return redirect()->back()->with('messages_error', 'Invalid Category')->withInput();
                    }

                    $amountByKategori = $this->MntTools->where('categoryId', $categoryId)->countAllResults();
                    $amountInKategori = (int) $dataKategori['jumlah'];
                    $namaKategori = $dataKategori['namaKategori'];

                    if ($amountByKategori >= $amountInKategori) {
                        return redirect()->back()->with('messages_error', 'Jumlah Tools sudah mencapai batas maksimum untuk kategori ' . $namaKategori . '!')->withInput();
                    }

                    $words = explode(' ', $namaKategori);
                    $prefix = '';

                    if (count($words) >= 3) {
                        $prefix = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1) . substr($words[2], 0, 1));
                    } elseif (count($words) > 1) {
                        $prefix = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                    } else {
                        $prefix = strtoupper(substr($words[0], 0, 2));
                    }
                    $prefix .= $categoryId;

                    $nextNumber = 1;
                    do {
                        $kodeAlat = sprintf('%s%03d', $prefix, $nextNumber);
                        $exists = $this->MntTools->where('kodeAlat', $kodeAlat)->countAllResults() > 0;
                        $nextNumber++;
                    } while ($exists);

                    $this->MntTools->insert([
                        'namaAlat'   => $rowData[0],
                        'kodeAlat'   => $kodeAlat,
                        'kondisi'    => $rowData[1],
                        'status'     => $rowData[2],
                        'categoryId' => $categoryId,
                    ]);
                }
            }

            return redirect()->back()->with('messages', 'Import Data Success!');
        }

        return redirect()->back()->with('messages_error', 'Import Data Failed!');
    }
    public function users()
    {
        $file = $this->request->getFile('usersFile');

        if ($file->isValid() && !$file->hasMoved()) {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $data = [];

            foreach ($sheet->getRowIterator(2) as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }
                if (!empty(trim($rowData[0])) && !empty(trim($rowData[1])) && !empty(trim($rowData[2])) && !empty(trim($rowData[3])) && !empty(trim($rowData[4])) && !empty(trim($rowData[5]))) {

                    $username = trim($rowData[0]);

                    $existsUsername = $this->Users->where('username', $username)->first();
                    if ($existsUsername) {
                        $existingUsernames[] = $username;
                        continue;
                    }

                    $newUsernames[] = $username;

                    $data[] = [
                        'username' => $username,
                        'password' => password_hash($rowData[1], PASSWORD_BCRYPT),
                        'nama' => trim($rowData[2]),
                        'role' => trim($rowData[3]),
                        'kontak' => trim($rowData[4]),
                        'status' => trim($rowData[5]),
                    ];
                }
            }
            if (!empty($data)) {
                $this->Users->insertBatch($data);
                if (empty($existingUsernames)) {
                    return redirect()->back()->with('messages', 'Import Data Success!');
                }
            }

            if (!empty($existingUsernames)) {
                $messages = "Username " . implode(', ', $existingUsernames) . ' Sudah digunakan, gunakan username lain!';
            }

            return redirect()->back()->with('messages_error', 'Import Data Failed!');
        }
    }
}
