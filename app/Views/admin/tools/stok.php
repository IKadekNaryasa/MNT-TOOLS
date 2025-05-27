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
                    <a href="<?= base_url('admin/tools'); ?>">MNT Tools</a>
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
                                    <h4 class="card-title">MNT Tools</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('admin/tools'); ?>">
                                    <button class="btn btn-warning btn-sm float-end mx-1">
                                        &laquo; Back
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="usersData" class="display table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th class="form-control-sm text-center">No</th>
                                                <th class="form-control-sm">Kategori</th>
                                                <th class="form-control-sm text-center">Tersedia</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            foreach ($categories as $category):
                                            ?>
                                                <tr>
                                                    <td class="form-control-sm text-center"><?= $no++; ?> </td>
                                                    <td class="form-control-sm"><?= $category['namaKategori']; ?></td>
                                                    <td class="form-control-sm text-center"><?= $category['jumlahTersedia']; ?></td>
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
    </div>
</div>
<!-- modal -->
<div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">New Inventory</span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url(); ?>admin/insertTool" method="post">
                    <?php csrf_field() ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group form-group-default">
                                <label>Nama Alat</label>
                                <input id="namaAlat" name="namaAlat" value="<?= old('namaAlat'); ?>" type="text" class="form-control" autocomplete="off" placeholder="" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Kondisi</label>
                                <input type="text" id="kondisi" name="kondisi" value="<?= old('kondisi'); ?>" type="text" class="form-control" autocomplete="off" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Status</label>
                                <select class="form-select" name="status" id="" required>
                                    <option value="tersedia" selected>Tersedia</option>
                                </select>
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



<?php $this->endSection(); ?>