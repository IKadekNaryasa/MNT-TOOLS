<?php $this->extend('template/template'); ?>
<?php $this->section('content'); ?>
<div class="container">
    <div class="page-inner">
        <!-- komponen -->
        <div class="page-category">
            <!-- isi page di sini -->
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="card card-profile border border-rounded-sm border-<?= (session('status') == 'active') ? 'success' : 'warning' ?> rounded">
                        <div
                            class="card-header"
                            style="background-image: url(<?= base_url(); ?>kaiadmin/assets/img/blogpost.jpg)">
                            <div class="profile-picture">
                                <div class="avatar avatar-xl">
                                    <img
                                        src="<?= base_url(); ?>kaiadmin/assets/img/profile.jpg"
                                        alt="..."
                                        class="avatar-img rounded-circle" />
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="user-profile text-center">
                                <div class="name"><?= session('nama'); ?></div>
                                <div class="job"><?= session('username'); ?></div>
                                <div class="job">
                                    <span class="badge badge-secondary mx-2"><?= session('role'); ?></span>
                                    <span class="badge badge-<?= (session('status') == 'active') ? 'success' : 'warning' ?>"><?= session('status'); ?></span>
                                </div>
                                <!-- <div class="desc">A man who hates loneliness</div> -->
                                <!-- <div class="social-media">
                                    <a
                                        class="btn btn-info btn-twitter btn-sm btn-link"
                                        href="#">
                                        <span class="btn-label just-icon"><i class="icon-social-twitter"></i>
                                        </span>
                                    </a>
                                    <a
                                        class="btn btn-primary btn-sm btn-link"
                                        rel="publisher"
                                        href="#">
                                        <span class="btn-label just-icon"><i class="icon-social-facebook"></i>
                                        </span>
                                    </a>
                                    <a
                                        class="btn btn-danger btn-sm btn-link"
                                        rel="publisher"
                                        href="#">
                                        <span class="btn-label just-icon"><i class="icon-social-instagram"></i>
                                        </span>
                                    </a>
                                </div> -->
                                <!-- <div class="view-profile">
                                    <a href="#" class="btn btn-secondary w-100">View Full Profile</a>
                                </div> -->
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row user-stats text-center">
                                <!-- <div class="col">
                                    <div class="number">125</div>
                                    <div class="title">Post</div>
                                </div>
                                <div class="col">
                                    <div class="number">25K</div>
                                    <div class="title">Followers</div>
                                </div> -->
                                <div class="col">
                                    <div class="number">Contact</div>
                                    <div class="title"><?= session('kontak'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>