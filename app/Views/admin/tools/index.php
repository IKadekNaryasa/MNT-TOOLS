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
                                <div class="div">
                                    <button class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addRowModal">
                                        <i class="fas fa-toolbox"></i>
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <button class="btn btn-secondary btn-sm float-end mx-1" data-bs-toggle="modal" data-bs-target="#importDataTools">
                                        <i class="fas fa-file-import"></i>
                                    </button>
                                    <form action="<?= base_url('admin/tools/cetak-qr'); ?>" method="post" target="_blank">
                                        <?php csrf_field() ?>
                                        <button class="btn btn-secondary btn-sm float-end mx-1">
                                            <i class="fas fa-qrcode"> Cetak QR</i>
                                        </button>
                                    </form>
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
                                        <th class="form-control-sm">Kode Alat</th>
                                        <th class="form-control-sm">Nama Alat</th>
                                        <th class="form-control-sm">Kategori</th>
                                        <th class="form-control-sm">Kondisi</th>
                                        <th class="form-control-sm">Status</th>
                                        <th class="form-control-sm">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($tools as $tool):
                                    ?>
                                        <tr>
                                            <td class="form-control-sm"><?= $no++; ?> </td>
                                            <td class="form-control-sm"><?= $tool['kodeAlat']; ?></td>
                                            <td class="form-control-sm"><?= $tool['namaAlat']; ?></td>
                                            <td class="form-control-sm"><?= $tool['namaKategori']; ?></td>
                                            <td class="form-control-sm"><?= $tool['kondisi']; ?></td>
                                            <td class="form-control-sm">
                                                <?php
                                                if ($tool['status'] == 'tersedia') {
                                                    echo '<span class="badge badge-success">tersedia</span>';
                                                } elseif ($tool['status'] == 'dipinjam') {
                                                    echo '<span class="badge badge-info">dipinjam</span>';
                                                } elseif ($tool['status'] == 'perawatan') {
                                                    echo '<span class="badge badge-warning">perawatan</span>';
                                                } elseif ($tool['status'] == 'perbaikan') {
                                                    echo '<span class="badge badge-secondary">perbaikan</span>';
                                                } else {
                                                    echo '<span class="badge badge-danger">Rusak</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="d-flex">

                                                <?php if ($tool['status'] != 'tersedia'): ?>
                                                    <button class="btn btn-warning btn-sm me-1" disabled><span class="fas fa-edit"></span></button>
                                                <?php else: ?>
                                                    <a href="<?= base_url(); ?>admin/tools/u/<?= encrypt_id($tool['mntToolsId']); ?>">
                                                        <button class="btn btn-warning btn-sm me-1"><span class="fas fa-edit"></span></button>
                                                    </a>
                                                <?php endif ?>

                                                <form id="deleteForm-<?= $tool['mntToolsId']; ?>" action="<?= base_url(); ?>admin/tools/delete" method="POST">
                                                    <?= csrf_field(); ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="text" hidden value="<?= encrypt_id($tool['mntToolsId']); ?>" name="mntToolsId">
                                                    <button type="button" class="btn btn-danger btn-sm me-1" onclick="confirmDelete('<?= $tool['mntToolsId']; ?>')"><span class="fas fa-trash"></span></button>
                                                </form>
                                                <form action="<?= base_url('admin/tools/cetak-single-qr'); ?>" method="post" target="_blank">
                                                    <?= csrf_field(); ?>
                                                    <input type="hidden" hidden value="<?= $tool['kodeAlat']; ?>" name="kodeAlat">
                                                    <button type="submit" class="btn btn-secondary btn-sm"><span class="fas fa-print"></span></button>
                                                </form>
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
<!-- modal import -->
<div class="modal fade" id="importDataTools" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">Import Data Tools</span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url(); ?>admin/mntTools/import" method="post" enctype="multipart/form-data">
                    <?php csrf_field() ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group ">
                                <input id="file" name="mntToolsFile" value="" type="file" class="form-control" autocomplete="off" placeholder="" required />
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

<script>
    function confirmDelete(mntToolsId) {
        Swal.fire({
            title: "Yakin menghapus data ini?",
            text: "Data lainnya dengan Inventory ini juga akan DIHAPUS",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm-' + mntToolsId).submit();
            }
        });
    }
</script>
<?php $this->endSection(); ?>