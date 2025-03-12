<?php $this->extend('template/template'); ?>
<?php $this->section('content'); ?>
<div class="container">
    <div class="page-inner">
        <div class="page-header">
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
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Form Update User</a>
                </li>
            </ul>
        </div>
        <!-- Mulai Form Update -->
        <form id="updateForm-<?= $users['usersId']; ?>" action="<?= base_url(); ?>admin/users/update" method="post">
            <?php csrf_field() ?>
            <div class="row">
                <input type="text" hidden name="_method" value="PUT">
                <input type="text" hidden name="usersId" value="<?= encrypt_id($users['usersId']); ?>">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Form Update</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group form-group-default">
                                        <label>Username</label>
                                        <input
                                            id="username"
                                            name="username"
                                            type="text"
                                            value="<?= !empty(old('username')) ? old('username') : $users['username']; ?>"
                                            class="form-control"
                                            placeholder="Fill Username" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-group-default">
                                        <label>Nama</label>
                                        <input
                                            id="nama"
                                            name="nama"
                                            type="text"
                                            value="<?= !empty(old('nama')) ? old('nama') : $users['nama']; ?>"
                                            class="form-control"
                                            placeholder="Fill Name" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-group-default">
                                        <label>Kontak</label>
                                        <input
                                            id="kontak"
                                            name="kontak"
                                            type="text"
                                            value="<?= !empty(old('kontak')) ? old('kontak') : $users['kontak']; ?>"
                                            class="form-control"
                                            placeholder="Fill Name" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-group-default">
                                        <label>Status</label>
                                        <select class="form-select" name="status" id="">
                                            <option value="<?= $users['status']; ?>" selected><?= $users['status']; ?></option>
                                            <option value="active">active</option>
                                            <option value="suspend">suspend</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-success" type="button" onclick="confirmUpdate('<?= $users['usersId']; ?>')">Submit</button>
                                    <a href="<?= base_url('admin/users'); ?>" class="btn btn-danger"> Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- Akhir Form Update -->
    </div>
</div>
<script>
    function confirmUpdate(usersId) {
        Swal.fire({
            title: "Yakin Mengubah data ini?",
            text: "Pastikan semua kolom terisi!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Ubah!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('updateForm-' + usersId).submit();
            }
        });
    }
</script>
<?php $this->endSection(); ?>