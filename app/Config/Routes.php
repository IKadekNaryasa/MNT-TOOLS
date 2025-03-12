<?php

use App\Controllers\Auth;
use CodeIgniter\Router\RouteCollection;
use App\Controllers\Export\GlobalExport;
use App\Controllers\Import\GlobalImport;
use App\Controllers\Head\View as HeadView;
use App\Controllers\Admin\Users as AdminUsers;
use App\Controllers\Admin\MntTools as AdminTools;
use App\Controllers\Admin\Request as AdminRequest;

use App\Controllers\Teknisi\Users as TeknisiUsers;
use App\Controllers\Head\Dashboard as HeadDashboard;
use App\Controllers\Admin\Dashboard as AdminDashboard;
use App\Controllers\Admin\Perawatan as AdminPerawatan;
use App\Controllers\Admin\Perbaikan as AdminPerbaikan;
use App\Controllers\Teknisi\Request as TeknisiRequest;
use App\Controllers\Admin\Categories as AdminCategories;

use App\Controllers\Admin\Peminjaman as AdminPeminjaman;



use App\Controllers\Teknisi\Dashboard as TeknisiDashboard;
use App\Controllers\Admin\DataInventaris as AdminInventaris;
use App\Controllers\Admin\Pengembalian as AdminPengembalian;
use App\Controllers\Teknisi\Peminjaman as TeknisiPeminjaman;
use App\Controllers\Teknisi\Pengembalian as TeknisiPengembalian;

/**
 * @var RouteCollection $routes
 */

// auth

$routes->group('', ['filter' => 'guest'], function ($routes) {
    $routes->get('/', [Auth::class, 'index']);
    $routes->get('/login', [Auth::class, 'index']);
    $routes->post('/authenticate', [Auth::class, 'authenticate']);
});

$routes->group('', ['filter' => 'loggedIn'], function ($routes) {
    $routes->get('/logout', [Auth::class, 'logout']);
    $routes->get('/changePasswordFirst', [Auth::class, 'viewChangePasswordFirst']);
    $routes->post('/changePasswordFirstTime', [Auth::class, 'changePasswordFirstTime']);
    $routes->post('/changePassword', [Auth::class, 'changePassword']);
});



$routes->group('admin', ['filter' => 'admin'], function ($routes) {

    // routes for view data
    $routes->get('/', [AdminDashboard::class, 'index']);
    $routes->get('dashboard', [AdminDashboard::class, 'index']);
    $routes->get('inventaris', [AdminInventaris::class, 'index']);
    $routes->get('categories', [AdminCategories::class, 'index']);
    $routes->get('tools', [AdminTools::class, 'index']);
    $routes->get('peminjaman', [AdminPeminjaman::class, 'index']);
    $routes->get('pengembalian', [AdminPengembalian::class, 'index']);
    $routes->get('perawatan', [AdminPerawatan::class, 'index']);
    $routes->get('perbaikan', [AdminPerbaikan::class, 'index']);
    $routes->get('request', [AdminRequest::class, 'index']);
    $routes->get('users', [AdminUsers::class, 'index']);

    $routes->get('profile', [AdminUsers::class, 'profile']);


    // routes for create peminjaman
    $routes->get('add-user-peminjaman', [AdminPeminjaman::class, 'viewAddUser']);
    $routes->post('search-user', [AdminPeminjaman::class, 'showUserByUsername']);
    $routes->get('add-tools-peminjaman', [AdminPeminjaman::class, 'viewAddTools']);
    $routes->post('addToolToCart', [AdminPeminjaman::class, 'toolToCart']);
    $routes->post('removeToolSession', [AdminPeminjaman::class, 'removeToolSession']);
    $routes->get('cart', [AdminPeminjaman::class, 'cart']);
    $routes->post('removeToolAndUserSession', [AdminPeminjaman::class, 'removeToolAndUserSession']);
    $routes->post('create-peminjaman', [AdminPeminjaman::class, 'createPeminjaman']);

    // cetak qr
    $routes->post('tools/cetak-qr', [AdminTools::class, 'cetakQR']);
    $routes->post('tools/cetak-single-qr', [AdminTools::class, 'cetakSingleQR']);


    // routes for insert data
    $routes->post('insertCategory', [AdminCategories::class, 'store']);
    $routes->post('insertInventaris', [AdminInventaris::class, 'store']);
    $routes->post('insertTool', [AdminTools::class, 'store']);
    $routes->post('insertUser', [AdminUsers::class, 'store']);

    // routes for update 
    $routes->get('category/u/(:any)', [AdminCategories::class, 'viewUpdate']);
    $routes->PUT('category/update', [AdminCategories::class, 'update']);
    $routes->get('tools/u/(:any)', [AdminTools::class, 'viewUpdate']);
    $routes->PUT('tools/update', [AdminTools::class, 'update']);
    $routes->PUT('perawatan/update', [AdminPerawatan::class, 'update']);
    $routes->PUT('perbaikan/update', [AdminPerbaikan::class, 'update']);
    $routes->PUT('pengembalian/update', [AdminPengembalian::class, 'update']);
    $routes->PUT('request/update', [AdminRequest::class, 'update']);
    $routes->get('users/u/(:any)', [AdminUsers::class, 'viewUpdate']);
    $routes->PUT('users/update', [AdminUsers::class, 'update']);

    // routes for Delete
    $routes->DELETE('users/delete', [AdminUsers::class, 'delete']);
    $routes->DELETE('tools/delete', [AdminTools::class, 'delete']);


    // routes for import
    $routes->post('categories/import', [GlobalImport::class, 'categories']);
    $routes->post('inventories/import', [GlobalImport::class, 'inventories']);
    $routes->post('mntTools/import', [GlobalImport::class, 'mntTools']);
    $routes->post('users/import', [GlobalImport::class, 'users']);

    // export
    $routes->post('peminjaman/export', [GlobalExport::class, 'peminjaman']);
    $routes->post('pengembalian/export', [GlobalExport::class, 'pengembalian']);
    $routes->post('perbaikan/export', [GlobalExport::class, 'perbaikan']);
    $routes->post('perawatan/export', [GlobalExport::class, 'perawatan']);
});


$routes->group('teknisi', ['filter' => 'teknisi'], function ($routes) {
    $routes->get('/', [TeknisiDashboard::class, 'index']);
    $routes->get('dashboard', [TeknisiDashboard::class, 'index']);
    $routes->get('peminjaman', [TeknisiPeminjaman::class, 'index']);
    $routes->get('pengembalian', [TeknisiPengembalian::class, 'index']);
    $routes->get('request', [TeknisiRequest::class, 'index']);
    $routes->get('request/new', [TeknisiRequest::class, 'viewRequest']);
    $routes->get('profile', [TeknisiUsers::class, 'profile']);

    $routes->post('pengembalian/ajukan-pengembalian', [TeknisiPengembalian::class, 'pengajuanPengembalian']);
    $routes->post('request/create', [TeknisiRequest::class, 'request']);
});


$routes->group('head', ['filter' => 'head'], function ($routes) {
    $routes->get('/', [HeadDashboard::class, 'index']);
    $routes->get('dashboard', [HeadDashboard::class, 'index']);
    $routes->get('export', [HeadView::class, 'index']);

    $routes->get('profile', [HeadView::class, 'profile']);


    $routes->post('inventory/export', [GlobalExport::class, 'inventory']);
    $routes->post('peminjaman/export', [GlobalExport::class, 'peminjaman']);
    $routes->post('pengembalian/export', [GlobalExport::class, 'pengembalian']);
    $routes->post('perbaikan/export', [GlobalExport::class, 'perbaikan']);
    $routes->post('perawatan/export', [GlobalExport::class, 'perawatan']);
});
