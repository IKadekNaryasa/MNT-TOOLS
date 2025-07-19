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
                    <a href="<?= base_url('admin/vendor'); ?>">Vendor</a>
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
                                    <h4 class="card-title">Data Vendor</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="div">
                                    <button class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addRowModal">
                                        <i class="fas fa-tasks"></i>
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
                                        <th class="text-center form-control-sm">No</th>
                                        <th class="form-control-sm">Nama Vendor</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($vendors as $vendor):
                                    ?>
                                        <tr>
                                            <td class="text-center form-control-sm"><?= $no++; ?> </td>
                                            <td class="form-control-sm"><?= $vendor['vendor']; ?></td>
                                            <td class="d-flex">
                                                <a href="<?= base_url(); ?>admin/vendor/u/<?= encrypt_id($vendor['vendorId']); ?>">
                                                    <button class="btn btn-warning btn-sm me-1 float-center"><span class="fas fa-edit"></span></button>
                                                </a>
                                                <form id="deleteForm-<?= $vendor['vendorId']; ?>" action="<?= base_url('admin/vendor/delete'); ?>" method="POST">
                                                    <?= csrf_field(); ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="vendorId" value="<?= encrypt_id($vendor['vendorId']); ?>">
                                                    <button type="button" class="btn btn-danger btn-sm me-1" onclick="confirmDelete('<?= $vendor['vendorId']; ?>')"><span class="fas fa-trash"></span></button>
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
                <h5 class="modal-title text-center">
                    <span class="fw-mediumbold">New Vendor</span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url(); ?>admin/insertVendor" method="post">
                    <?php csrf_field() ?>
                    <div class="row">
                        <div class="col"></div>
                        <div class="col-md-12">
                            <div class="form-group form-group-default">
                                <label>Nama Vendor</label>
                                <input style="text-transform: uppercase;" id="namaVendor" name="namaVendor" value="<?= old('namaVendor'); ?>" type="text" class="form-control" autocomplete="off" placeholder="input Vendor" />
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

<script>
    function confirmDelete(vendorId) {
        Swal.fire({
            title: "Yakin menghapus data ini?",
            text: "Data lainnya dengan Vendor ini juga akan DIHAPUS",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm-' + vendorId).submit();
            }
        });
    }
</script>

<?php $this->endSection(); ?>