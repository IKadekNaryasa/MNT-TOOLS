<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Request extends BaseController
{
    public function index()
    {
        $data = [
            'request' => $this->Requests->getAll(),
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
