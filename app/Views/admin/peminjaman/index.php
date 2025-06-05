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
                    <a href="<?= base_url('admin/peminjaman'); ?>">Peminjaman</a>
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
                                    <h4 class="card-title">Data Peminjaman</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="div">
                                    <!-- <a href="<?= base_url('admin/add-user-peminjaman'); ?>">
                                        <button class="btn btn-primary btn-sm float-end">
                                            <i class="fas fa-book">
                                                <i class="fas fa-plus"></i>
                                            </i>
                                        </button>
                                    </a> -->
                                    <button class="btn btn-success btn-sm float-end mx-1" data-bs-toggle="modal" data-bs-target="#exportDataPeminjaman">
                                        <i class="fas fa-file-export"></i>
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
                                        <th class="form-control-sm">Kode Peminjaman</th>
                                        <th class="form-control-sm">Peminjam</th>
                                        <th class="form-control-sm">Admin</th>
                                        <th class="form-control-sm">Tanggal</th>
                                        <th class="form-control-sm">Keterangan</th>
                                        <th class="form-control-sm">Status</th>
                                        <th class="form-control-sm">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($peminjaman as $pnj):
                                    ?>
                                        <tr>
                                            <td class="form-control-sm"><?= $no++; ?> </td>
                                            <td class="form-control-sm"><?= $pnj['peminjamanCode']; ?></td>
                                            <td class="form-control-sm"><?= $pnj['namaPeminjam']; ?></td>
                                            <td class="form-control-sm"><?= $pnj['namaAdmin']; ?></td>
                                            <td class="form-control-sm"><?= $pnj['tanggalPinjam']; ?></td>
                                            <td class="form-control-sm"><?= $pnj['keteranganPeminjaman']; ?></td>
                                            <td class="form-control-sm">
                                                <?php
                                                if ($pnj['statusPeminjaman'] == 'dipinjam') {
                                                    echo '<span class="badge badge-info">dipinjam</span>';
                                                } elseif ($pnj['statusPeminjaman'] == 'pengembalian diajukan') {
                                                    echo '<span class="badge badge-warning">pengembalian diajukan</span>';
                                                } else {
                                                    echo '<span class="badge badge-success">dikembalikan</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="d-flex">
                                                <button class="btn btn-secondary btn-sm me-1 float-center" data-bs-toggle="modal" data-bs-target="#peminjamanDetailModal-<?= $pnj['peminjamanCode']; ?>">
                                                    <i class="fas fa-info-circle"></i> Detail
                                                </button>
                                                <!-- Modal -->
                                                <div class="modal fade" id="peminjamanDetailModal-<?= $pnj['peminjamanCode']; ?>" tabindex="-2" role="dialog" aria-labelledby="peminjamanDetailModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="peminjamanDetailModalLabel">Detail Peminjaman - <?= $pnj['peminjamanCode']; ?></h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No</th>
                                                                            <th>Kode Alat</th>
                                                                            <th>Nama Alat</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $kodeAlat = explode(',', $pnj['kodeAlat']);
                                                                        $namaAlat = explode(',', $pnj['namaAlat']);
                                                                        $no = 1;
                                                                        foreach ($kodeAlat  as $index => $kode):
                                                                        ?>
                                                                            <tr>
                                                                                <td class="text-center"><?= $no++; ?></td>
                                                                                <td class="text-center"><?= $kode; ?></td>
                                                                                <td class="text-center"><?= $namaAlat[$index] ?? 'Tidak Diketahui'; ?></td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
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

<!-- modal export -->
<div class="modal fade" id="exportDataPeminjaman" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">Export Data Peminjaman</span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('admin/peminjaman/export'); ?>" method="post">
                    <div class="row justify-content-center px-4">
                        <?php csrf_field() ?>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label for="">Tanggal Awal</label>
                                <input type="Date" class="form-control" name="tanggalAwal" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label for="">Tanggal Akhir</label>
                                <input type="Date" class="form-control" name="tanggalAkhir" required>
                            </div>
                        </div>
                        <button class="btn btn-secondary btn-sm my-2" type="submit">
                            <i class="fas fa-file-export"> Export</i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>