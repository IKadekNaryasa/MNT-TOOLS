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
                    <a href="<?= base_url('admin/categories'); ?>">Categories</a>
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
                                    <h4 class="card-title">Data Kategori</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="div">
                                    <button class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addRowModal">
                                        <i class="fas fa-tasks"></i>
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <button class="btn btn-secondary btn-sm float-end mx-2" data-bs-toggle="modal" data-bs-target="#importModal">
                                        <i class="fas fa-file-import"></i>
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
                                        <th class="text-center form-control-sm">No</th>
                                        <th class="form-control-sm">Kategori</th>
                                        <th class="text-center form-control-sm">Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($categories as $category):
                                    ?>
                                        <tr>
                                            <td class="text-center form-control-sm"><?= $no++; ?> </td>
                                            <td class="form-control-sm"><?= $category['namaKategori']; ?></td>
                                            <td class="text-center form-control-sm "><?= $category['jumlah']; ?></td>
                                            <td class="d-flex">
                                                <div class="">
                                                    <a href="<?= base_url(); ?>admin/category/u/<?= encrypt_id($category['categoryId']); ?>">
                                                        <button class="btn btn-warning btn-sm me-1 float-center"><span class="fas fa-edit"></span></button>
                                                    </a>
                                                </div>
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
<!-- modal -->
<div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-center">
                    <span class="fw-mediumbold">New Kategori</span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url(); ?>admin/insertCategory" method="post">
                    <?php csrf_field() ?>
                    <div class="row">
                        <div class="col"></div>
                        <div class="col-sm-6">
                            <div class="form-group form-group-default">
                                <label>Nama Kategori</label>
                                <input style="text-transform: uppercase;" id="namaKategori" name="namaKategori" value="<?= old('namaKategori'); ?>" type="text" class="form-control" autocomplete="off" placeholder="input kategori" />
                            </div>
                        </div>
                        <div class="col"></div>
                    </div>
                    <div class="modal-footer ms-0 border-0">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" id="addRowButton" class="btn btn-primary">
                                    Add
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" name="btn-close">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- import modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-center">
                    <span class="fw-mediumbold">Import Data Kategori</span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url(); ?>admin/categories/import" method="post" enctype="multipart/form-data">
                    <?php csrf_field() ?>
                    <div class="row">
                        <div class="col"></div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input name="fileCategory" type="file" class="form-control" autocomplete="off" placeholder="input kategori" />
                            </div>
                        </div>
                        <div class="col"></div>
                    </div>
                    <div class="modal-footer ms-0 border-0">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" id="addRowButton" class="btn btn-primary">
                                    Add
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" name="btn-close">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>