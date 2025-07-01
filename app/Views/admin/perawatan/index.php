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
                    <a href="<?= base_url('admin/perawatan'); ?>">Perawatan</a>
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
                                    <h4 class="card-title">Data Perawatan</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-success btn-sm float-end mx-1" data-bs-toggle="modal" data-bs-target="#exportDataPerawatan">
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
                                        <th class="form-control-sm">Kode Alat</th>
                                        <th class="form-control-sm">Nama Alat</th>
                                        <th class="form-control-sm">Mulai</th>
                                        <th class="form-control-sm">Selesai</th>
                                        <th class="form-control-sm">Status</th>
                                        <th class="form-control-sm">Deskripsi</th>
                                        <th class="form-control-sm">Biaya</th>
                                        <th class="form-control-sm">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($perawatan as $prwt): ?>
                                        <tr>
                                            <td class="form-control-sm"><?= $no++; ?></td>
                                            <td class="form-control-sm"><?= $prwt['kodeAlat']; ?></td>
                                            <td class="form-control-sm"><?= $prwt['namaAlat']; ?></td>
                                            <td class="form-control-sm"><?= $prwt['tanggalPerawatan'] ?? '-'; ?></td>
                                            <td class="form-control-sm"><?= $prwt['tanggalSelesai'] ?? '-'; ?></td>
                                            <td class="form-control-sm">
                                                <?php
                                                if ($prwt['statusPerawatan'] == 'on progres') {
                                                    echo '<span class="badge badge-warning">On Progres</span>';
                                                } elseif ($prwt['statusPerawatan'] == 'selesai') {
                                                    echo '<span class="badge badge-success">Selesai</span>';
                                                } else {
                                                    echo '<span class="badge badge-danger">tidak valid</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="form-control-sm"><?= $prwt['deskripsi'] ?? '-'; ?></td>
                                            <td class="form-control-sm"><?= $prwt['biaya'] ?? '-'; ?></td>
                                            <td class="form-control-sm">
                                                <!-- Tombol hanya aktif jika ada field yang belum terisi -->
                                                <button
                                                    class="btn btn-primary btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modal-<?= $prwt['perawatanId']; ?>"
                                                    <?= ($prwt['tanggalSelesai'] && $prwt['deskripsi'] && $prwt['biaya']) ? 'disabled' : ''; ?>>
                                                    Selesai
                                                </button>
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
<!-- modal -->
<?php foreach ($perawatan as $prwt2): ?>
    <div class="modal fade" id="modal-<?= $prwt2['perawatanId']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Selesai Perawatan</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('admin/perawatan/update'); ?>" method="post">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="perawatanId" value="<?= $prwt2['perawatanId']; ?>">
                        <input type="hidden" name="kodeAlat" value="<?= $prwt2['kodeAlat']; ?>">
                        <input type="hidden" name="_method" value="PUT">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-group-default">
                                    <label>Tanggal Selesai</label>
                                    <input id="tanggalSelesai" name="tanggalSelesai" value="<?= old('tanggalSelesai'); ?>" type="date" class="form-control" autocomplete="off" placeholder="" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-group-default">
                                    <label>Biaya</label>
                                    <input type="number" id="biaya" name="biaya" value="<?= old('biaya'); ?>" class="form-control" autocomplete="off" placeholder="" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group form-group-default">
                                    <label>Deskripsi</label>
                                    <input type="text" id="deskripsi" name="deskripsi" value="<?= old('deskripsi'); ?>" class="form-control" autocomplete="off" placeholder="" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="submit" id="addRowButton" class="btn btn-primary">Selesai</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" name="btn-close">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- modal export -->
<div class="modal fade" id="exportDataPerawatan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">Export Data Perawatan</span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('admin/perawatan/export'); ?>" method="post">
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