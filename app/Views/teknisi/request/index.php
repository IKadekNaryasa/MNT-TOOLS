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
                    <a href="<?= base_url('teknisi/request'); ?>">Request</a>
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
                                    <h4 class="card-title">Data Request Peminjaman</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="div">
                                    <a href="<?= base_url(); ?>teknisi/request/new">
                                        <button class="btn btn-primary btn-sm float-end">
                                            Ajukan peminjaman
                                        </button>
                                    </a>
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
                                        <th class="form-control-sm">Kode Request</th>
                                        <th class="form-control-sm">Tgl Pengajuan</th>
                                        <th class="form-control-sm">Status</th>
                                        <th class="form-control-sm">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($requests as $item): ?>
                                        <tr>
                                            <td class="form-control-sm"><?= $no++; ?></td>
                                            <td class="form-control-sm"><?= $item['permintaanCode']; ?></td>
                                            <td class="form-control-sm"><?= $item['tanggalPermintaan']; ?></td>
                                            <td class="form-control-sm">
                                                <?php
                                                if ($item['status'] == 'pending') {
                                                    echo '<span class="badge badge-info">pending</span>';
                                                } elseif ($item['status'] == 'disetujui') {
                                                    echo '<span class="badge badge-success">disetujui</span>';
                                                } else {
                                                    echo '<span class="badge badge-danger">ditolak</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="d-flex">
                                                <button class="btn btn-secondary btn-sm me-1 float-center" data-bs-toggle="modal" data-bs-target="#detailModal-<?= $item['permintaanCode']; ?>">
                                                    <i class="fas fa-info-circle"></i> Detail
                                                </button>
                                                <!-- Modal -->
                                                <div class="modal fade" id="detailModal-<?= $item['permintaanCode']; ?>" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="detailModalLabel">Detail Permintaan - <?= $item['permintaanCode']; ?></h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <ul>
                                                                    <?php
                                                                    $kategoriList = explode(', ', $item['kategori']);

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
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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