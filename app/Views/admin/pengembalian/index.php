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
                    <a href="<?= base_url('admin/pengembalian'); ?>">Pengembalian</a>
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
                                    <h4 class="card-title">Data Pengembalian</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-success btn-sm float-end mx-1" data-bs-toggle="modal" data-bs-target="#exportDataPengembalian">
                                    <i class="fas fa-file-export"></i>
                                </button>
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
                                        <th class="form-control-sm">Kembali</th>
                                        <th class="form-control-sm">Keterangan </th>
                                        <th class="form-control-sm">By Admin</th>
                                        <th class="form-control-sm">Status</th>
                                        <th class="form-control-sm">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($pengembalian as $pnmbl):
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?> </td>
                                            <td class="form-control-sm"><?= $pnmbl['peminjamanCode']; ?></td>
                                            <td class="form-control-sm"><?= $pnmbl['tanggalKembali']; ?></td>
                                            <td class="form-control-sm"><?= $pnmbl['keteranganPengembalian']; ?></td>
                                            <td class="form-control-sm"><?= $pnmbl['nama']; ?></td>
                                            <td>
                                                <?php
                                                if ($pnmbl['statusPengembalian'] == 'diajukan') {
                                                    echo '<span class="badge badge-info">diajukan</span>';
                                                } elseif ($pnmbl['statusPengembalian'] == 'disetujui') {
                                                    echo '<span class="badge badge-success">disetujui</span>';
                                                } else {
                                                    echo '<span class="badge badge-danger">ditolak</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="d-flex form-control-sm">
                                                <button class="btn btn-secondary btn-sm me-1 float-center" data-bs-toggle="modal" data-bs-target="#pengembalianDetail-<?= $pnmbl['pengembalianId']; ?>">
                                                    </i> Detail
                                                </button>
                                                <button class="btn btn-primary btn-sm me-1 float-center" data-bs-toggle="modal" data-bs-target="#konfirmasiModal-<?= $pnmbl['pengembalianId']; ?>">
                                                    Confirm
                                                </button>

                                                <!-- Modal konfirmasi -->
                                                <div class="modal fade" id="konfirmasiModal-<?= $pnmbl['pengembalianId']; ?>" tabindex="-1" role="dialog" aria-labelledby="detailKonfirmasiModal" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="detailModalLabel">Konfirmasi Pengembalian</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="pengembalian/update" method="post" id="form-konfirmasi-<?= $pnmbl['pengembalianId']; ?>">
                                                                <div class="modal-body">
                                                                    <?= csrf_field() ?>
                                                                    <input type="hidden" name="pengembalianId" value="<?= $pnmbl['pengembalianId']; ?>">
                                                                    <input type="hidden" name="_method" value="PUT">
                                                                    <input type="hidden" name="peminjamanCode" value="<?= $pnmbl['peminjamanCode']; ?>">
                                                                    <input type="hidden" name="userId" value="<?= $pnmbl['usersId']; ?>">

                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>No</th>
                                                                                <th>Kode Alat</th>
                                                                                <th>Nama Alat</th>
                                                                                <th>Status Alat</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $i = 0;
                                                                            $alatDipinjamAda = false;
                                                                            $alatRusak = [];

                                                                            foreach ($pnmbl['alat'] as $alat):
                                                                                $statusAlat = trim($alat['statusAlat'] ?? '');
                                                                                if ($statusAlat === 'dipinjam') {
                                                                                    $alatDipinjamAda = true;
                                                                                    $i++;
                                                                            ?>
                                                                                    <tr>
                                                                                        <td><?= $i ?></td>
                                                                                        <td>
                                                                                            <?= $alat['kodeAlat'] ?>
                                                                                            <input type="hidden" name="alat[<?= $i ?>][kode]" value="<?= $alat['kodeAlat'] ?>">
                                                                                        </td>
                                                                                        <td><?= $alat['namaAlat'] ?></td>
                                                                                        <td>
                                                                                            <select name="alat[<?= $i ?>][status]" class="form-select status-select" required>
                                                                                                <option value="belum dikembalikan" selected>Belum Dikembalikan</option>
                                                                                                <option value="dikembalikan">Dikembalikan</option>
                                                                                                <option value="rusak/hilang">Rusak / Hilang</option>
                                                                                            </select>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php
                                                                                } elseif ($statusAlat === 'rusak') {
                                                                                    $i++;
                                                                                    $alatRusak[] = $alat['kodeAlat'];
                                                                                ?>
                                                                                    <tr>
                                                                                        <td><?= $i ?></td>
                                                                                        <td><?= $alat['kodeAlat'] ?></td>
                                                                                        <td><?= $alat['namaAlat'] ?></td>
                                                                                        <td><span class="text-danger fw-bold">Rusak / Hilang</span></td>
                                                                                    </tr>
                                                                            <?php
                                                                                }
                                                                            endforeach;
                                                                            ?>

                                                                            <?php if (!$alatDipinjamAda): ?>
                                                                                <tr>
                                                                                    <td colspan="4" class="text-center fw-bold <?= empty($alatRusak) ? 'text-success' : 'text-warning' ?>">
                                                                                        <?php if (empty($alatRusak)): ?>
                                                                                            Semua alat telah dikembalikan.
                                                                                        <?php else: ?>
                                                                                            Semua alat telah dikembalikan, kecuali alat <?= implode(', ', $alatRusak); ?> rusak/hilang.
                                                                                        <?php endif; ?>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php endif; ?>


                                                                        </tbody>

                                                                    </table>
                                                                    <?php if ($alatDipinjamAda): ?>

                                                                        <div class="form-group mt-3">
                                                                            <label>Keterangan</label>
                                                                            <input type="text" name="keteranganPengembalian" class="form-control" required>
                                                                        </div>
                                                                    <?php endif; ?>

                                                                </div>

                                                                <?php if ($alatDipinjamAda): ?>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-primary btn-confirmasi">Simpan</button>
                                                                    </div>
                                                                <?php endif; ?>

                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal -->
                                                <div class="modal fade" id="pengembalianDetail-<?= $pnmbl['pengembalianId']; ?>" tabindex="-2" role="dialog" aria-labelledby="pengembalianDetailLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="pengembalianDetailLabel">Alat yang dipinjam</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="">No</th>
                                                                            <th>Kode Alat</th>
                                                                            <th>Nama Alat</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $no = 1;
                                                                        foreach ($pnmbl['alat'] as $alat):
                                                                        ?>
                                                                            <tr>
                                                                                <td class="text-center"><?= $no++; ?></td>
                                                                                <td class="text-center"><?= $alat['kodeAlat']; ?></td>
                                                                                <td class="text-center"><?= $alat['namaAlat'] ?? 'Tidak Diketahui'; ?></td>
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
<div class="modal fade" id="exportDataPengembalian" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">Export Data Pengembalian</span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('admin/pengembalian/export'); ?>" method="post">
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