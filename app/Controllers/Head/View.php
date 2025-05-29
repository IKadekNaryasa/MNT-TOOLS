<?php

namespace App\Controllers\Head;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class View extends BaseController
{
    public function index()
    {
        $data = [
            'categories' => $this->Categories->findAll(),
            'active' => 'export'
        ];
        return view('head/export/index', $data);
    }

    public function profile()
    {
        $data = [
            'active' => ''
        ];
        return view('head/profile', $data);
    }
}
