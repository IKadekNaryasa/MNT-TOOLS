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
                    <a href="<?= base_url('admin/inventaris'); ?>">Inventaris</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Form Update Inventaris</a>
                </li>
            </ul>
        </div>
        <!-- Mulai Form Update -->
        <form id="updateForm-<?= $inventaris['dataInventarisId']; ?>" action="<?= base_url(); ?>admin/inventaris/update" method="post">
            <?php csrf_field() ?>
            <div class="row">
                <input type="text" hidden name="_method" value="PUT">
                <input type="text" hidden name="dataInventarisId" value="<?= encrypt_id($inventaris['dataInventarisId']); ?>">
                <input type="text" hidden name="categoryId" value="<?= encrypt_id($inventaris['categoryId']); ?>">
                <div class="col-sm-4">
                    <div class="form-group form-group-default">
                        <label>Tanggal</label>
                        <input id="tanggalDI" name="tanggalDI" value="<?= $inventaris['tanggalDI'] ?? old('tanggalDI') ?>" type="date" class="form-control" autocomplete="off" placeholder="" required />
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group form-group-default">
                        <label>Jumlah</label>
                        <input id="jumlahDI" name="jumlahDI" value="<?= $inventaris['jumlahDI'] ?? old('jumlahDI'); ?>" type="number" class="form-control" autocomplete="off" placeholder="" required />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-group-default">
                        <label>Vendor</label>
                        <input type="text" id="vendor" name="vendor" value="<?= $inventaris['vendor'] ?? old('vendor'); ?>" type="text" class="form-control" autocomplete="off" placeholder="" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-group-default">
                        <label>Harga Satuan</label>
                        <input type="number" id="harga" name="harga" value="<?= $inventaris['harga'] ?? old('harga'); ?>" class="form-control" autocomplete="off" placeholder="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-group-default">
                        <label>Total Harga</label>
                        <input type="number" id="total" name="total" value="<?= $inventaris['total'] ?? old('total'); ?>" class="form-control" autocomplete="off" placeholder="">
                    </div>
                </div>
                <div class="col-md-12">
                    <span class="text-danger">* Isikan salah satu harga (harga satuan atau harga total atau keduanya)</span>
                </div>
            </div>
            <div class="modal-footer border-0">
                <a href="<?= base_url('admin/inventaris'); ?>">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" name="btn-close">
                        Batal
                    </button>
                </a>
                <button class="btn btn-primary ms-5" type="button" onclick="confirmUpdate('<?= $inventaris['dataInventarisId']; ?>')">
                    Submit
                </button>
            </div>

        </form>
        <!-- Akhir Form Update -->
    </div>
</div>
<script>
    function confirmUpdate(dataInventarisId) {
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
                document.getElementById('updateForm-' + dataInventarisId).submit();
            }
        });
    }
</script>
<?php $this->endSection(); ?>