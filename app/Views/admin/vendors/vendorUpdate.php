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
                    <a href="<?= base_url('admin/vendor'); ?>">Vendor</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Form Update Vendor</a>
                </li>
            </ul>
        </div>
        <!-- Mulai Form Update -->
        <form id="updateForm-<?= $vendor['vendorId']; ?>" action="<?= base_url(); ?>admin/vendor/update" method="post">
            <?php csrf_field() ?>
            <div class="row">
                <input type="text" hidden name="_method" value="PUT">
                <input type="text" hidden name="vendorId" value="<?= encrypt_id($vendor['vendorId']); ?>">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Form Update</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col"></div>
                                <div class="col-md-4">
                                    <div class="form-group form-group-default">
                                        <label>Nama Vendor</label>
                                        <input
                                            id="vendor"
                                            name="vendor"
                                            type="text"
                                            value="<?= !empty(old('vendor')) ? old('vendor') : $vendor['vendor']; ?>"
                                            class="form-control" style="text-transform: uppercase;"
                                            placeholder="Fill Nama Vendor" autofocus />
                                    </div>
                                </div>
                                <div class="col"></div>
                            </div>
                        </div>
                        <div class="card-action">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-success" type="button" onclick="confirmUpdate('<?= $vendor['vendorId']; ?>')">Submit</button>
                                    <a href="<?= base_url('admin/vendor'); ?>" class="btn btn-danger"> Cancel</a>
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
    function confirmUpdate(vendorId) {
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
                document.getElementById('updateForm-' + vendorId).submit();
            }
        });
    }
</script>
<?php $this->endSection(); ?>