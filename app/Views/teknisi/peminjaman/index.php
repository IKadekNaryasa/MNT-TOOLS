<?php $this->extend('template/teknisiTemplate'); ?>
<?php $this->section('content'); ?>
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <!-- <h3 class="fw-bold mb-3">Update Data User</h3> -->
            <ul class="breadcrumbs ms-0">
                <li class="nav-home">
                    <a href="<?= base_url('teknisi/dashboard'); ?>">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('teknisi/peminjaman'); ?>">Peminjaman</a>
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
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="usersData" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="form-control-sm">No</th>
                                        <th class="form-control-sm">Kode</th>
                                        <th class="form-control-sm">Tgl</th>
                                        <th class="form-control-sm">Note</th>
                                        <th class="form-control-sm">By Admin</th>
                                        <th class="form-control-sm">Status</th>
                                        <th class="form-control-sm">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($peminjaman as $item): ?>
                                        <tr>
                                            <td class="form-control-sm"><?= $no++; ?></td>
                                            <td class="form-control-sm"><?= $item['peminjamanCode']; ?></td>
                                            <td class="form-control-sm"><?= $item['tanggalPinjam']; ?></td>
                                            <td class="form-control-sm"><?= $item['keteranganPeminjaman']; ?></td>
                                            <td class="form-control-sm"><?= $item['nama']; ?></td>
                                            <td class="form-control-sm">
                                                <?php
                                                if ($item['statusPeminjaman'] == 'dipinjam') {
                                                    echo '<span class="badge badge-info">dipinjam</span>';
                                                } elseif ($item['statusPeminjaman'] == 'pengembalian diajukan') {
                                                    echo '<span class="badge badge-warning">pengembalian diajukan</span>';
                                                } else {
                                                    echo '<span class="badge badge-success">dikembalikan</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="d-flex">
                                                <button class="btn btn-secondary btn-sm me-1 float-center" data-bs-toggle="modal" data-bs-target="#detailModal-<?= $item['peminjamanCode']; ?>">
                                                    <i class="fas fa-info-circle"></i> Detail
                                                </button>
                                                <!-- Modal -->
                                                <div class="modal fade" id="detailModal-<?= $item['peminjamanCode']; ?>" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="peminjamanDetailModalLabel">Detail Peminjaman - <?= $item['peminjamanCode']; ?></h5>
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
                                                                        $kodeAlat = explode(',', $item['kodeAlat']);
                                                                        $namaAlat = explode(',', $item['namaAlat']);
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
                                                                <?php if ($item['statusPeminjaman'] != 'dikembalikan' && $item['statusPeminjaman'] != 'pengembalian diajukan'): ?>
                                                                    <form action="<?= base_url('teknisi/pengembalian/ajukan-pengembalian'); ?>" method="post">
                                                                        <?= csrf_field(); ?>
                                                                        <input type="hidden" name="peminjamanCode" value="<?= $item['peminjamanCode']; ?>">
                                                                        <button type="submit" class="btn btn-warning btn-sm mx-5" data-bs-dismiss="modal">Ajukan Pengembalian</button>
                                                                    </form> <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>