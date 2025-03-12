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
                                <div class="col"></div>
                                <div class="<?= session('UID') ? 'col-8' : 'col-12'; ?>">
                                    <div class="card-title d-block text-center">Find & Add User First</div>
                                </div>
                                <?php if (session('UID')) : ?>
                                    <div class="col-2">
                                        <a href="<?= base_url('admin/add-tools-peminjaman'); ?>">
                                            <button class="btn btn-secondary btn-sm" type="button" onclick="confirmSubmit()">Next &raquo;</button>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (session('UID')): ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-group ms-0">
                                                <span class="input-group-text" id="basic-addon1">U</span>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    placeholder="username"
                                                    aria-label="username"
                                                    value="<?= session('Uusername'); ?>"
                                                    readonly
                                                    aria-describedby="basic-addon1" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon1">N</span>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    placeholder="nama"
                                                    aria-label="nama"
                                                    value="<?= session('Uname'); ?>"
                                                    readonly
                                                    aria-describedby="basic-addon1" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-group ms-0">
                                                <span class="input-group-text" id="basic-addon1">HP</span>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    placeholder="no_hp"
                                                    aria-label="no_hp"
                                                    value="<?= session('UnoHp'); ?>"
                                                    readonly
                                                    aria-describedby="basic-addon1" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="tab-content mt-2 mb-3" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-user" role="tabpanel" aria-labelledby="pills-user-tab">
                                    <form id="updateForm" action="<?= base_url('admin/search-user'); ?>" method="post">
                                        <?php csrf_field() ?>
                                        <div class="row">
                                            <div class="col-md-12 mt-3">
                                                <div class="card-body">
                                                    <div class="row mb-5">
                                                        <div class="col"></div>
                                                        <div class="col-md-6">
                                                            <div class="input-group">
                                                                <input id="username" name="username" type="text" value="<?= session('Uusername') ? session('Uusername') : old('username'); ?>" class="form-control" autofocus placeholder="Type Username" />
                                                                <button class="btn btn-black btn-border" type="submit"> Add </button>
                                                            </div>
                                                        </div>
                                                        <div class="col"></div>
                                                    </div>
                                                </div>
                                                <div class="card-action">
                                                    <div class="row">
                                                        <div class="col-md-12 text-center">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
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