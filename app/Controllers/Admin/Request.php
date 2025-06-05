<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Request extends BaseController
{
    public function index()
    {
        $request = $this->Requests->getAll();
        $finalRequest = [];
        foreach ($request as $req) {
            $req['transaction'] = false;

            $exists = $this->Peminjaman->where('requestCode', $req['permintaanCode'])->first();

            if ($exists) {
                $req['transaction'] = true;
            }

            $finalRequest[] = $req;
        }
        $data = [
            'request' => $finalRequest,
            'active' => 'pengajuan-peminjaman'
        ];

        // return $this->response->setJSON($data);

        return view('admin/request/index', $data);
    }

    public function update()
    {
        $data = [
            'permintaanCode' => $this->request->getPost('permintaanCode'),
            'status' => $this->request->getPost('status'),
        ];
        $query = $this->Requests->where('permintaanCode', $data['permintaanCode'])->set(['status' => $data['status']])->update();
        if (!$query) {
            return redirect()->to(base_url('admin/request'))->with('messaes_error', 'Failed set status!');
        }
        $s = $data['status'];
        return redirect()->to(base_url('admin/request'))->with('messages', "Success Set Status $s");
    }
}
