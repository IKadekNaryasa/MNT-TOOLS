<?php $this->extend('template/template'); ?>
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
                    <a href="<?= base_url('admin/dataInventaris'); ?>">Data Inventaris</a>
                </li>
            </ul>
        </div>
        <!-- komponen -->
        <div class="page-category">
            <!-- isi page di sini -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="d-flex align-items-center">
                                    <h4 class="card-title">Data Inventaris</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="div">
                                    <button class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addRowModal">
                                        <i class="fas fa-shopping-cart"></i>
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="usersData" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="form-control-sm">No</th>
                                        <th class="form-control-sm">Kategori</th>
                                        <th class="form-control-sm">Tanggal</th>
                                        <th class="form-control-sm">Vendor</th>
                                        <th class="form-control-sm">Jumlah</th>
                                        <th class="form-control-sm">Total Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($inventaris as $inv):
                                    ?>
                                        <tr>
                                            <td class="form-control-sm"><?= $no++; ?> </td>
                                            <td class="form-control-sm"><?= $inv['namaKategori']; ?></td>
                                            <td class="form-control-sm"><?= $inv['tanggalDI']; ?></td>
                                            <td class="form-control-sm"><?= $inv['vendor']; ?></td>
                                            <td class="form-control-sm"><?= $inv['jumlahDI']; ?></td>
                                            <td class="form-control-sm">Rp <?= number_format($inv['total'], 0, ',', '.'); ?></td>
                                            <td class="d-flex form-control-sm">
                                                <a href="<?= base_url(); ?>admin/inventaris/u//<?= encrypt_id($inv['dataInventarisId']); ?>">
                                                    <button class="btn btn-warning btn-sm me-1"><span class="fas fa-edit"></span></button>
                                                </a>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- modal insert -->
<div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">New Data Inventaris</span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url(); ?>admin/insertInventaris" method="post">
                    <?php csrf_field() ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group form-group-default">
                                <label>Tanggal</label>
                                <input id="tanggalDI" name="tanggalDI" value="<?= date('Y-m-d'); ?>" readonly type="text" class="form-control" autocomplete="off" placeholder="" required />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-group-default">
                                <label>Jumlah</label>
                                <input id="jumlahDI" name="jumlahDI" value="<?= old('jumlahDI'); ?>" type="number" class="form-control" autocomplete="off" placeholder="" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Harga Satuan</label>
                                <input type="number" id="harga" name="harga" value="<?= old('harga'); ?>" class="form-control" autocomplete="off" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Total Harga</label>
                                <input type="number" id="total" name="total" value="<?= old('total'); ?>" class="form-control" autocomplete="off" placeholder="">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Kategori</label>
                                <select class="form-select" name="categoryId" id="" required>
                                    <option disabled value="" selected>Pilih Kategori</option>
                                    <?php foreach ($categories as $kg): ?>
                                        <option value="<?= $kg['categoryId']; ?>"><?= $kg['namaKategori']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Vendor</label>
                                <select class="form-select" name="vendorId" id="" required>
                                    <option disabled value="" selected>Pilih Vendor</option>
                                    <?php foreach ($vendors as $vn): ?>
                                        <option value="<?= $vn['vendorId']; ?>"><?= $vn['vendor']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Nama Alat</label>
                                <input type="text" id="namaAlat" name="namaAlat" value="<?= old('namaAlat'); ?>" type="text" class="form-control" autocomplete="off" placeholder="" required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <span class="text-danger">* Isikan salah satu harga (harga satuan atau harga total atau keduanya)</span>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" id="addRowButton" class="btn btn-primary">
                            Add
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" name="btn-close">
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- modal import -->
<div class="modal fade" id="importDataInventaris" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">Import Data Inventaris</span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url(); ?>admin/inventories/import" method="post" enctype="multipart/form-data">
                    <?php csrf_field() ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="file" name="inventoriesFile" class="form-control" autocomplete="off" placeholder="" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" id="addRowButton" class="btn btn-primary">
                            Add
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" name="btn-close">
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- modal export -->
<div class="modal fade" id="exportDataInventaris" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">Export Data Inventaris</span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('admin/inventory/export'); ?>" method="post">
                    <div class="row justify-content-start px-4">
                        <?php csrf_field() ?>
                        <div class="col-md-6">
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
                    </div>
                    <div class="row justify-content-center px-4">
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
<script>
    function confirmDelete(dataInventarisId) {
        Swal.fire({
            title: "Yakin menghapus data ini?",
            text: "Data lainnya dengan pengadaan ini juga akan DIHAPUS",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm-' + dataInventarisId).submit();
            }
        });
    }
</script>
<?php $this->endSection(); ?>