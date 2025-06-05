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
                    <a href="<?= base_url('admin/pengajuanPermintaan'); ?>">Permintaan</a>
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
                                    <h4 class="card-title">Data Permintaan</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="usersData" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center form-control-sm">No</th>
                                        <th class="form-control-sm">Kode</th>
                                        <th class="form-control-sm">Nama User</th>
                                        <th class="text-start form-control-sm">Kontak</th>
                                        <th class=" text-start form-control-sm">Tanggal</th>
                                        <th class="form-control-sm">Status</th>
                                        <th class="form-control-sm">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($request as $req): ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td class="form-control-sm"><?= $req['permintaanCode']; ?></td>
                                            <td class="form-control-sm"><?= $req['nama']; ?></td>
                                            <td class="form-control-sm"><?= $req['kontak']; ?></td>
                                            <td class="text-start form-control-sm"><?= $req['tanggalPermintaan']; ?></td>
                                            <td class="form-control-sm">
                                                <?php
                                                if ($req['status'] == 'pending') {
                                                    echo '<span class="badge badge-info">pending</span>';
                                                } elseif ($req['status'] == 'disetujui') {
                                                    echo '<span class="badge badge-success">disetujui</span>';
                                                } else {
                                                    echo '<span class="badge badge-danger">ditolak</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="d-flex form-control-sm">
                                                <button class="btn btn-secondary btn-sm me-1 float-center" data-bs-toggle="modal" data-bs-target="#detailModal-<?= $req['permintaanCode']; ?>">
                                                    <i class="fas fa-info-circle"></i> Detail
                                                </button>
                                                <button class="btn btn-primary btn-sm me-1 float-center" data-bs-toggle="modal" data-bs-target="#konfirmasiModal-<?= $req['permintaanCode']; ?>">
                                                    Confirm
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal konfirmasi -->
                                        <div class="modal fade" id="konfirmasiModal-<?= $req['permintaanCode']; ?>" tabindex="-1" role="dialog" aria-labelledby="detailKonfirmasiModal" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="detailModalLabel">Konfirmasi Permintaan - <?= $req['permintaanCode']; ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="request/update" method="post">
                                                        <?= csrf_field(); ?>
                                                        <div class="modal-body">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="permintaanCode" value="<?= $req['permintaanCode']; ?>">
                                                            <input type="hidden" name="_method" value="PUT">
                                                            <div class="row">
                                                                <div class="col-md-3"></div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>
                                                                            <input type="radio" class="ms-0" name="status" value="disetujui" required> Disetujui
                                                                            <input type="radio" class="ms-5" name="status" value="ditolak" required> Ditolak
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3"></div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal -->
                                        <div class="modal fade" id="detailModal-<?= $req['permintaanCode']; ?>" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="detailModalLabel">Detail Permintaan - <?= $req['permintaanCode']; ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <ul>
                                                            <?php
                                                            $kategoriList = explode(', ', $req['kategori']);

                                                            $kategoriCounts = array_count_values($kategoriList);

                                                            foreach ($kategoriCounts as $kategori => $count): ?>
                                                                <li>
                                                                    <?= $kategori; ?>
                                                                    <?= $count > 1 ? ' x' . $count : ' x1'; ?>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <?php if ($req['status'] == 'disetujui'): ?>
                                                            <?php if ($req['transaction'] != true): ?>
                                                                <a href="<?= base_url("admin/add-tools-peminjaman/" . $req['usersId']) . '/' . $req['permintaanCode']; ?>">
                                                                    <button class="btn btn-sm btn-primary">Transaksi Peminjaman</button>
                                                                </a>
                                                            <?php elseif ($req['transaction'] == true): ?>
                                                                <button class="btn btn-sm btn-success">Transaksi Selesai!</button>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

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
<?php $this->endSection(); ?>