<?php

namespace App\Controllers;

use App\Models\Users;
use App\Models\Request;
use App\Models\Category;
use App\Models\MntTools;
use App\Models\Perawatan;
use App\Models\Perbaikan;
use App\Models\Peminjaman;
use CodeIgniter\Controller;
use App\Models\Pengembalian;
use Psr\Log\LoggerInterface;
use App\Models\DataInventaris;
use App\Models\DetailPeminjaman;
use App\Models\DetailPermintaan;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');
    }

    protected $Categories;
    protected $DataInventaris;
    protected $DetailPeminjaman;
    protected $DetailPermintaan;
    protected $MntTools;
    protected $Peminjaman;
    protected $Pengembalian;
    protected $Perawatan;
    protected $Perbaikan;
    protected $Requests;
    protected $Users;
    public function __construct()
    {
        $this->Categories = new Category();
        $this->DataInventaris = new DataInventaris();
        $this->DetailPeminjaman = new DetailPeminjaman();
        $this->DetailPermintaan = new DetailPermintaan();
        $this->MntTools = new MntTools();
        $this->Peminjaman = new Peminjaman();
        $this->Pengembalian = new Pengembalian();
        $this->Perawatan = new Perawatan();
        $this->Perbaikan = new Perbaikan();
        $this->Requests = new Request();
        $this->Users = new Users();
    }
}
