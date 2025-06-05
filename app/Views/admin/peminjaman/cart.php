<?php $this->extend('template/template'); ?>
<?php $this->section('content'); ?>
<div class="container">
    <div class="page-inner">
        <!-- komponen -->
        <div class="page-category">
            <!-- isi page di sini -->
            <div class="row">
                <div class="col"></div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <div class="row text-center">
                                <div class="col-2">
                                    <a href="<?= base_url('admin/add-tools-peminjaman/' . session('UID')) . '/' . session('requestCode'); ?>">
                                        <button class="btn btn-secondary btn-sm">&laquo;Back</button>
                                    </a>
                                </div>
                                <div class="col-8">
                                    <div class="card-title d-block text-center">Cart</div>
                                </div>
                                <div class="col-2">
                                    <form action="<?= base_url('admin/create-peminjaman'); ?>" id="submitCart" method="post">
                                        <?php csrf_field() ?>
                                        <button class="btn btn-secondary btn-sm" type="button" onclick="confirmSubmit()">Submit</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="input-group ms-0">
                                            <span class="input-group-text" id="basic-addon1">Tgl</span>
                                            <input
                                                type="date"
                                                class="form-control"
                                                placeholder="tgl_peminjaman"
                                                aria-label="tgl_peminjaman"
                                                value="<?= date('Y-m-d'); ?>"
                                                name="tglPinjam"
                                                aria-describedby="basic-addon1" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="input-group ms-0">
                                            <span class="input-group-text" id="basic-addon1">Name</span>
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder=""
                                                aria-label=""
                                                value="<?= session('Uname'); ?>"
                                                name=""
                                                aria-describedby="basic-addon1" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="input-group ms-0">
                                            <span class="input-group-text" id="basic-addon1">RC</span>
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder=""
                                                aria-label=""
                                                value="<?= session('requestCode'); ?>"
                                                name="requestCode"
                                                aria-describedby="basic-addon1" />
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="usersId" value="<?= session('UID'); ?>">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="input-group ms-0">
                                            <span class="input-group-text" id="basic-addon1">Ket.</span>
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder="Type Keterangan"
                                                aria-label="ket"
                                                required
                                                autofocus
                                                name="keteranganPeminjaman"
                                                aria-describedby="basic-addon1" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col"></div>
                                <div class="col-12">
                                    <div class="row mx-1 my-1">
                                        <div class="col-md-12 border mb-2 border-primary bg-secondary table-dark">
                                            <div class="row">
                                                <div class="col-2">
                                                    No
                                                </div>
                                                <div class="col-5">
                                                    Kode Barang
                                                </div>
                                                <div class="col-5">
                                                    Barang
                                                </div>
                                            </div>
                                        </div>
                                        <?php if (session()->get('cart')): ?>
                                            <div class="col-md-12 border border-secondary rounded">
                                                <?php $no = 1;
                                                foreach (session('cart') as $item): ?>
                                                    <div class="row">
                                                        <div class="col-2">
                                                            <?= $no++; ?>
                                                        </div>
                                                        <input type="hidden" name="kodeAlat[]" value="<?= esc($item['kodeAlat']); ?>">
                                                        <input type="hidden" name="statusPeminjaman" value="dipinjam">
                                                        <div class="col-5">
                                                            <?= esc($item['kodeAlat']); ?>
                                                        </div>
                                                        <div class="col-5">
                                                            <?= esc($item['namaAlat']); ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <p class="text-center">No Items In Cart!</p>
                                        <?php endif; ?>
                                        <input type="hidden" name="usersId" value="<?= esc(session('UID')); ?>">
                                        </form>
                                    </div>
                                    <div class="col-12 my-5">
                                        <!-- form batalkan semua -->
                                        <div class="row text-end">
                                            <form action="<?= base_url('admin/removeToolAndUserSession'); ?>" method="post" id="formBatalkanSemua">
                                                <?php csrf_field() ?>
                                                <button class="btn btn-danger btn-sm" type="button" onclick="confirmBatalkanSemua()">Batalkan Semua</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col"></div>

            </div>
        </div>
    </div>
</div>
<script>
    function confirmSubmit() {
        Swal.fire({
            title: "Yakin Menyimpan?",
            text: "Data Pada Cart Akan dimasukan kedalam Data peminjaman",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Simpan!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('submitCart').submit();
            }
        });
    }

    function confirmBatalkanSemua() {
        Swal.fire({
            title: "Yakin Membatalkan Transaksi?",
            text: "Membatalkan akan menghapus data pada transkasi ini",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Batalkan!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formBatalkanSemua').submit();
            }
        });
    }
</script>
<?php $this->endSection(); ?>