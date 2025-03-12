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
                    <a href="<?= base_url('admin/categories'); ?>">Kategori</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Form Update Kategori</a>
                </li>
            </ul>
        </div>
        <!-- Mulai Form Update -->
        <form id="updateForm-<?= $categories['categoryId']; ?>" action="<?= base_url(); ?>admin/category/update" method="post">
            <?php csrf_field() ?>
            <div class="row">
                <input type="text" hidden name="_method" value="PUT">
                <input type="text" hidden name="categoryId" value="<?= encrypt_id($categories['categoryId']); ?>">
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
                                        <label>Nama Kategori</label>
                                        <input
                                            id="namaKategori"
                                            name="namaKategori"
                                            type="text"
                                            value="<?= !empty(old('namaKategori')) ? old('namaKategori') : $categories['namaKategori']; ?>"
                                            class="form-control"
                                            placeholder="Fill Nama Kategori" autofocus />
                                    </div>
                                </div>
                                <div class="col"></div>
                            </div>
                        </div>
                        <div class="card-action">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-success" type="button" onclick="confirmUpdate('<?= $categories['categoryId']; ?>')">Submit</button>
                                    <a href="<?= base_url('admin/categories'); ?>" class="btn btn-danger"> Cancel</a>
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
    function confirmUpdate(categoryId) {
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
                document.getElementById('updateForm-' + categoryId).submit();
            }
        });
    }
</script>
<?php $this->endSection(); ?>