<?php

namespace App\Controllers\Teknisi;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Users extends BaseController
{
    public function index()
    {
        //
    }

    public function profile()
    {
        $data = [
            'active' => ''
        ];
        return view('teknisi/profile', $data);
    }
}
