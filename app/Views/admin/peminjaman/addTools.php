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
                                    <a href="<?= base_url('admin/add-user-peminjaman'); ?>">
                                        <button class="btn btn-secondary btn-sm">&laquo;Back</button>
                                    </a>
                                </div>
                                <div class="col-8">
                                    <div class="card-title d-block text-center">Add Tools</div>
                                </div>
                                <div class="col-2">
                                    <a href="<?= base_url('admin/cart'); ?>">
                                        <button class="btn btn-secondary btn-sm">Next &raquo;</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <form action="<?= base_url('admin/addToolToCart'); ?>" method="post">
                                            <?php csrf_field() ?>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="kodeAlat" placeholder="Type Kode Alat" aria-label="" required aria-describedby="basic-addon1" autofocus />
                                                <button class="btn btn-black btn-border" type="submit"> Add </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col"></div>
                            </div>
                            <div class="row">
                                <div class="col"></div>
                                <div class="col-12">
                                    <div class="row mx-1 my-1">
                                        <div class="col-md-12 border mb-2 border-primary bg-secondary table-dark">
                                            <div class="row">
                                                <div class="col-3">
                                                    Kode Alat
                                                </div>
                                                <div class="col-7">
                                                    Barang
                                                </div>
                                                <div class="col-2">
                                                    Action
                                                </div>
                                            </div>
                                        </div>
                                        <?php if (session()->get('cart')) : ?>
                                            <div class="col-md-12 border border-secondary rounded py-1">
                                                <?php foreach (session('cart') as $item): ?>
                                                    <div class="row mb-1">
                                                        <div class="col-3">
                                                            <?= esc($item['kodeAlat']) ?>
                                                        </div>
                                                        <div class="col-7">
                                                            <?= esc($item['namaAlat']) ?>
                                                        </div>
                                                        <div class="col-2">
                                                            <form action="<?= base_url('admin/removeToolSession'); ?>" method="post">
                                                                <div class="col-2">
                                                                    <input type="hidden" name="sessionIndexInv" value="<?= esc($item['kodeAlat']); ?>">
                                                                    <button type="submit" class="btn-sm btn btn-danger" value="">X</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <p class="text-center">No Items In Cart!</p>
                                        <?php endif; ?>
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

<?php $this->endSection(); ?>