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
                                        <th class="form-control-sm">Kode Peminjaman</th>
                                        <th class="form-control-sm">Kembali</th>
                                        <th class="form-control-sm">Note</th>
                                        <th class="form-control-sm">By Admin</th>
                                        <th class="form-control-sm">Status</th>
                                        <th class="form-control-sm">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($pengembalian as $item): ?>
                                        <tr>
                                            <td class="form-control-sm"><?= $no++; ?></td>
                                            <td class="form-control-sm"><?= $item['peminjamanCode']; ?></td>
                                            <td class="form-control-sm"><?= $item['tanggalKembali']; ?></td>
                                            <td class="form-control-sm"><?= $item['keteranganPengembalian']; ?></td>
                                            <td class="form-control-sm"><?= $item['nama']; ?></td>
                                            <td class="form-control-sm">
                                                <?php
                                                if ($item['statusPengembalian'] == 'diajukan') {
                                                    echo '<span class="badge badge-info">diajukan</span>';
                                                } elseif ($item['statusPengembalian'] == 'disetujui') {
                                                    echo '<span class="badge badge-success">disetujui</span>';
                                                } else {
                                                    echo '<span class="badge badge-danger">tidak valid</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="d-flex">
                                                <button class="btn btn-secondary btn-sm me-1 float-center" data-bs-toggle="modal" data-bs-target="#detailModal-<?= $item['peminjamanCode']; ?>">
                                                    <i class="fas fa-info-circle"></i> Detail
                                                </button>
                                                <!-- Modal -->
                                                <div class="modal fade" id="detailModal-<?= $item['peminjamanCode']; ?>" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="peminjamanDetailModalLabel">Detail Pengembalian - <?= $item['peminjamanCode']; ?></h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="table table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No</th>
                                                                            <th>Kode Alat</th>
                                                                            <th>Nama Alat</th>
                                                                            <th>Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $kodeAlat = explode(',', $item['kodeAlat']);
                                                                        $namaAlat = explode(',', $item['namaAlat']);
                                                                        $no = 1;
                                                                        foreach ($kodeAlat as $index => $kode):
                                                                            $kode = trim($kode);
                                                                            $statusAlatSaatIni = $statusAlat[$kode] ?? 'tidak diketahui';


                                                                            if ($statusAlatSaatIni == 'tersedia') {
                                                                                $statusText = 'Dikembalikan';
                                                                                $badgeClass = 'badge-success';
                                                                            } elseif ($statusAlatSaatIni == 'rusak') {
                                                                                $statusText = 'Rusak';
                                                                                $badgeClass = 'badge-danger';
                                                                            } elseif ($statusAlatSaatIni == 'dipinjam') {
                                                                                $statusText = 'Belum Dikembalikan';
                                                                                $badgeClass = 'badge-warning';
                                                                            } else {
                                                                                $statusText = 'Tidak Diketahui';
                                                                                $badgeClass = 'badge-secondary';
                                                                            }
                                                                        ?>
                                                                            <tr>
                                                                                <td class="text-center"><?= $no++; ?></td>
                                                                                <td class="text-center"><?= $kode; ?></td>
                                                                                <td class="text-center"><?= $namaAlat[$index] ?? 'Tidak Diketahui'; ?></td>
                                                                                <td class="text-center">
                                                                                    <span class="badge <?= $badgeClass; ?>"><?= $statusText; ?></span>
                                                                                </td>
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