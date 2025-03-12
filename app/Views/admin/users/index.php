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
                    <a href="<?= base_url('admin/users'); ?>">Users</a>
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
                                    <h4 class="card-title">Data User</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="div">
                                    <button class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addRowModal">
                                        <i class="fas fa-user-plus"></i>
                                    </button>
                                    <button class="btn btn-secondary btn-sm float-end mx-1" data-bs-toggle="modal" data-bs-target="#importDataUsers">
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
                                        <th class="form-control-sm">No</th>
                                        <th class="form-control-sm">Nama</th>
                                        <th class="form-control-sm">Kontak</th>
                                        <th class="form-control-sm">Username</th>
                                        <th class="form-control-sm">Role</th>
                                        <th class="form-control-sm">Status</th>
                                        <th class="form-control-sm">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($users as $usr):
                                    ?>
                                        <tr>
                                            <td class="form-control-sm"><?= $no++; ?> </td>
                                            <td class="form-control-sm"><?= $usr['nama']; ?></td>
                                            <td class="form-control-sm"><?= $usr['kontak']; ?></td>
                                            <td class="form-control-sm"><?= $usr['username']; ?></td>
                                            <td class="form-control-sm"><?= $usr['role']; ?></td>
                                            <td class="form-control-sm">
                                                <?php
                                                if ($usr['status'] == 'active') {
                                                    echo '<span class="badge badge-success">active</span>';
                                                } elseif ($usr['status'] == 'suspend') {
                                                    echo '<span class="badge badge-warning">suspend</span>';
                                                } else {
                                                    echo '<span class="badge badge-danger">tdak valid</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="d-flex">
                                                <a href="<?= base_url(); ?>admin/users/u/<?= encrypt_id($usr['usersId']); ?>">
                                                    <button class="btn btn-warning btn-sm me-1"><span class="fas fa-edit"></span></button>
                                                </a>
                                                <form id="deleteForm-<?= $usr['usersId']; ?>" action="<?= base_url(); ?>admin/users/delete" method="POST">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="text" hidden value="<?= encrypt_id($usr['usersId']); ?>" name="usersId">
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('<?= $usr['usersId']; ?>')"><span class="fas fa-trash"></span></button>
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
                    <span class="fw-mediumbold">New User</span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url(); ?>admin/insertUser" method="post">
                    <?php csrf_field() ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group form-group-default">
                                <label>Nama</label>
                                <input id="nama" name="nama" value="<?= old('nama'); ?>" type="text" class="form-control" autocomplete="off" placeholder="" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-group-default">
                                <label>Username</label>
                                <input id="username" name="username" value="<?= old('username'); ?>" type="text" class="form-control" autocomplete="off" placeholder="" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>kontak</label>
                                <input type="text" id="kontak" name="kontak" value="<?= old('kontak'); ?>" type="text" class="form-control" autocomplete="off" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Role</label>
                                <select class="form-select" name="role" id="">
                                    <option disabled value="" selected>Pilih Role</option>
                                    <option value="admin">admin</option>
                                    <option value="teknisi">teknisi</option>
                                    <option value="head">head</option>
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
<div class="modal fade" id="importDataUsers" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">Import Data User</span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url(); ?>admin/users/import" method="post" enctype="multipart/form-data">
                    <?php csrf_field() ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group ">
                                <input id="file" name="usersFile" value="" type="file" class="form-control" autocomplete="off" placeholder="" required />
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
    function confirmDelete(usersId) {
        Swal.fire({
            title: "Yakin menghapus data ini?",
            text: "Data lainnya dengan user ini juga akan DIHAPUS",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm-' + usersId).submit();
            }
        });
    }
</script>
<?php $this->endSection(); ?>