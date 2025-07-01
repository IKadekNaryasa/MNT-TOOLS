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
                    <a href="<?= base_url('admin/tools'); ?>">Tools</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Form Update Tools</a>
                </li>
            </ul>
        </div>
        <!-- Mulai Form Update -->
        <form id="updateForm-<?= $tools[0]['mntToolsId']; ?>" action="<?= base_url(); ?>admin/tools/update" method="post">
            <?php csrf_field() ?>
            <div class="row">
                <input type="text" hidden name="_method" value="PUT">
                <input type="text" hidden name="mntToolsId" value="<?= encrypt_id($tools[0]['mntToolsId']); ?>">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Form Update</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group form-group-default">
                                        <label>Nama Alat</label>
                                        <input
                                            id="namaAlat"
                                            name="namaAlat"
                                            type="text"
                                            value="<?= !empty(old('namaAlat')) ? old('namaAlat') : $tools[0]['namaAlat']; ?>"
                                            class="form-control"
                                            autocomplete="off"
                                            placeholder="Fill namaAlat" />
                                    </div>
                                </div>
                                <div class="col-md-4" hidden>
                                    <div class="form-group form-group-default">
                                        <label>Kode Alat</label>
                                        <input
                                            id="kodeAlat"
                                            name="kodeAlat"
                                            hidden
                                            type="text"
                                            value="<?= !empty(old('kodeAlat')) ? old('kodeAlat') : $tools[0]['kodeAlat']; ?>"
                                            class="form-control"
                                            readonly
                                            autocomplete="off"
                                            placeholder="Fill Name" />
                                    </div>
                                </div>
                                <div class="col-md-4" hidden>
                                    <div class="form-group form-group-default">
                                        <label>Kondisi</label>
                                        <input
                                            id="kondisi"
                                            name="kondisi"
                                            type="text"
                                            value="<?= !empty(old('kondisi')) ? old('kondisi') : $tools[0]['kondisi']; ?>"
                                            class="form-control"
                                            autocomplete="off"
                                            placeholder="Fill Name" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-group-default">
                                        <label>Status</label>
                                        <select class="form-select" name="status" id="status" <?= $isEditable ? '' : 'disabled'; ?> required>
                                            <option value="<?= $tools[0]['status']; ?>" selected>
                                                <?= ucfirst($tools[0]['status']); ?>
                                            </option>
                                            <?php if ($isEditable): ?>
                                                <option value="tersedia">Tersedia</option>
                                                <option value="perawatan">Perawatan</option>
                                                <option value="perbaikan">Perbaikan</option>
                                                <option value="rusak">Barang Rusak</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4" hidden>
                                    <div class="form-group form-group-default">
                                        <label>Kategori</label>
                                        <select class="form-select" name="categoryId" id="" readonly>
                                            <option value="<?= $tools[0]['categoryId']; ?>" selected><?= $tools[0]['namaKategori']; ?></option>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?= $category['categoryId']; ?>"><?= $category['namaKategori']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-success" type="button" onclick="confirmUpdate('<?= $tools[0]['mntToolsId']; ?>')">Submit</button>
                                    <a href="<?= base_url('admin/tools'); ?>" class="btn btn-danger"> Cancel</a>
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
    function confirmUpdate(mntToolsId) {
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
                document.getElementById('updateForm-' + mntToolsId).submit();
            }
        });
    }
</script>
<?php $this->endSection(); ?>