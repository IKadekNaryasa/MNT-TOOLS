<?php $this->extend('template/headDivisiTemplate'); ?>
<?php $this->section('content'); ?>

<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <!-- <h3 class="fw-bold mb-3">Update Data User</h3> -->
            <ul class="breadcrumbs ms-0">
                <li class="nav-home">
                    <a href="<?= base_url('admin/dashboard'); ?>">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('head/export'); ?>">Export</a>
                </li>
            </ul>
        </div>
        <!-- komponen -->
        <div class="page-category">
            <!-- isi page di sini -->
            <div class="row">

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-center">
                                        <h4 class="card-title">Export Data Inventaris</h4>
                                    </div>
                                </div>
                                <!-- <div class="col-md-3">
                                <div class="div">
                                    <button class="btn btn-secondary btn-sm float-end mx-2" data-bs-toggle="modal" data-bs-target="#importModal">
                                        <i class="fas fa-file-import"></i>
                                    </button>
                                </div>
                            </div> -->
                            </div>
                        </div>
                        <div class="card-body ">
                            <form action="<?= base_url('head/inventory/export'); ?>" method="post">
                                <div class="row justify-content-center px-4">
                                    <?php csrf_field() ?>
                                    <div class="col-md-4">
                                        <div class="form-group form-group-default">
                                            <label for="">Kategori</label>
                                            <select name="kategori" class="form-control" required>
                                                <option selected disabled>Pilih katgori...</option>
                                                <option value="00">Semua Kategori</option>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?= $category['categoryId']; ?>"><?= $category['namaKategori']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-group-default">
                                            <label for="">Tanggal Awal</label>
                                            <input type="Date" class="form-control" name="tanggalAwal" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-group-default">
                                            <label for="">Tanggal Akhir</label>
                                            <input type="Date" class="form-control" name="tanggalAkhir" required>
                                        </div>
                                    </div>
                                    <button class="btn btn-secondary btn-sm my-2" type="submit">
                                        <i class="fas fa-file-export"> Export</i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-center">
                                        <h4 class="card-title">Export Data Peminjaman</h4>
                                    </div>
                                </div>
                                <!-- <div class="col-md-3">
                                <div class="div">
                                    <button class="btn btn-secondary btn-sm float-end mx-2" data-bs-toggle="modal" data-bs-target="#importModal">
                                        <i class="fas fa-file-import"></i>
                                    </button>
                                </div>
                            </div> -->
                            </div>
                        </div>
                        <div class="card-body ">
                            <form action="<?= base_url('head/peminjaman/export'); ?>" method="post">
                                <div class="row justify-content-center px-4">
                                    <?php csrf_field() ?>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default">
                                            <label for="">Tanggal Awal</label>
                                            <input type="Date" class="form-control" name="tanggalAwal" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default">
                                            <label for="">Tanggal Akhir</label>
                                            <input type="Date" class="form-control" name="tanggalAkhir" required>
                                        </div>
                                    </div>
                                    <button class="btn btn-secondary btn-sm my-2" type="submit">
                                        <i class="fas fa-file-export"> Export</i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-center">
                                        <h4 class="card-title">Export Data Pengembalian</h4>
                                    </div>
                                </div>
                                <!-- <div class="col-md-3">
                                <div class="div">
                                    <button class="btn btn-secondary btn-sm float-end mx-2" data-bs-toggle="modal" data-bs-target="#importModal">
                                        <i class="fas fa-file-import"></i>
                                    </button>
                                </div>
                            </div> -->
                            </div>
                        </div>
                        <div class="card-body ">
                            <form action="<?= base_url('head/pengembalian/export'); ?>" method="post">
                                <div class="row justify-content-center px-4">
                                    <?php csrf_field() ?>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default">
                                            <label for="">Tanggal Awal</label>
                                            <input type="Date" class="form-control" name="tanggalAwal" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default">
                                            <label for="">Tanggal Akhir</label>
                                            <input type="Date" class="form-control" name="tanggalAkhir" required>
                                        </div>
                                    </div>
                                    <button class="btn btn-secondary btn-sm my-2" type="submit">
                                        <i class="fas fa-file-export"> Export</i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-center">
                                        <h4 class="card-title">Export Data Perbaikan</h4>
                                    </div>
                                </div>
                                <!-- <div class="col-md-3">
                                <div class="div">
                                    <button class="btn btn-secondary btn-sm float-end mx-2" data-bs-toggle="modal" data-bs-target="#importModal">
                                        <i class="fas fa-file-import"></i>
                                    </button>
                                </div>
                            </div> -->
                            </div>
                        </div>
                        <div class="card-body ">
                            <form action="<?= base_url('head/perbaikan/export'); ?>" method="post">
                                <div class="row justify-content-center px-4">
                                    <?php csrf_field() ?>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default">
                                            <label for="">Tanggal Awal</label>
                                            <input type="Date" class="form-control" name="tanggalAwal" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default">
                                            <label for="">Tanggal Akhir</label>
                                            <input type="Date" class="form-control" name="tanggalAkhir" required>
                                        </div>
                                    </div>
                                    <button class="btn btn-secondary btn-sm my-2" type="submit">
                                        <i class="fas fa-file-export"> Export</i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-center">
                                        <h4 class="card-title">Export Data Perawatan</h4>
                                    </div>
                                </div>
                                <!-- <div class="col-md-3">
                                <div class="div">
                                    <button class="btn btn-secondary btn-sm float-end mx-2" data-bs-toggle="modal" data-bs-target="#importModal">
                                        <i class="fas fa-file-import"></i>
                                    </button>
                                </div>
                            </div> -->
                            </div>
                        </div>
                        <div class="card-body ">
                            <form action="<?= base_url('head/perawatan/export'); ?>" method="post">
                                <div class="row justify-content-center px-4">
                                    <?php csrf_field() ?>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default">
                                            <label for="">Tanggal Awal</label>
                                            <input type="Date" class="form-control" name="tanggalAwal" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default">
                                            <label for="">Tanggal Akhir</label>
                                            <input type="Date" class="form-control" name="tanggalAkhir" required>
                                        </div>
                                    </div>
                                    <button class="btn btn-secondary btn-sm my-2" type="submit">
                                        <i class="fas fa-file-export"> Export</i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>