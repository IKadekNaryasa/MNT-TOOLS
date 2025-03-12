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
        ];
        return view('head/export/index', $data);
    }

    public function profile()
    {
        return view('head/profile');
    }
}
